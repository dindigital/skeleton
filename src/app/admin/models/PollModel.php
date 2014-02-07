<?php

namespace src\app\admin\models;

use src\app\admin\validators\PollValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\PollOptionModel;

/**
 *
 * @package app.models
 */
class PollModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('poll');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'question LIKE ?' => '%' . $arrFilters['question'] . '%',
        'is_del = ?' => '0'
    );

    $select = new Select('poll');
    $select->addField('id_poll');
    $select->addField('active');
    $select->addField('question');
    $select->addField('inc_date');
    $select->where($arrCriteria);
    $select->order_by('inc_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function getById ( $id = null )
  {
    $row = parent::getById($id);

    $opt = new PollOptionModel;
    $row['option'] = $opt->getByIdPoll($row['id_poll']);

    return $row;
  }

  public function getNew ()
  {
    $row = parent::getNew();
    $row['option'] = array();

    return $row;
  }

  public function insert ( $info )
  {
    //_# Valida a Pergunta
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setQuestion($info['question']);
    $validator->setTotalOptions(count($info['option']));
    $validator->setDefaultUri($info['question'], $this->getId(), 'enquete');
    $validator->setIncDate();

    //_# Valida e armazena as opções em array
    $arr_opt = array();

    foreach ( $info['option'] as $option ) {
      $opt = new PollOptionModel;
      $opt->validate_insert(array(
          'id_poll' => $this->getId(),
          'option' => $option
      ));

      $arr_opt[] = $opt;
    }

    //_# Arremeça exceptions
    $validator->throwException();

    //_# Salva o questionário container
    $this->_dao->insert($this->_table);
    $this->log('C', $info['question'], $this->_table);

    //_# Salva as questões
    foreach ( $arr_opt as $opt ) {
      $opt->insert();
    }
  }

  public function update ( $info )
  {
    //_# Valida a Pergunta
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setQuestion($info['question']);
    $validator->setDefaultUri($info['question'], $this->getId(), 'enquete');

    //_# Valida e armazena as opções em array
    $arr_opt = array();

    foreach ( $info['option'] as $option_id => $option ) {
      $opt = new PollOptionModel;
      $opt->setId($option_id);
      $opt->validate_update(array(
          'option' => $option
      ));

      $arr_opt[] = $opt;
    }

    //_# Arremeça exceptions
    $validator->throwException();

    //_# Salva o questionário container
    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_poll = ?' => $this->getId()));
    $this->log('U', $info['question'], $this->_table, $tableHistory);

    //_# Salva as questões
    foreach ( $arr_opt as $opt ) {
      $opt->update();
    }
  }

}

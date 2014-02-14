<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
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

  public function insert ( $input )
  {
    //_# Valida a Pergunta
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['question'], 'enquete');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('question', 'Pergunta');
    $validator->validateArrayNotEmpty('option', 'Alternativa');

    //_# Valida e armazena as opções em array
    $arr_opt = array();

    foreach ( $input['option'] as $option ) {
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
    $this->dao_insert();

    //_# Salva as questões
    foreach ( $arr_opt as $opt ) {
      $opt->insert();
    }
  }

  public function update ( $input )
  {
    //_# Valida a Pergunta
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['question'], 'enquete', $input['uri']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('question', 'Pergunta');

    //_# Valida e armazena as opções em array
    $arr_opt = array();

    foreach ( $input['option'] as $option_id => $option ) {
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
    $this->dao_update();

    //_# Salva as questões
    foreach ( $arr_opt as $opt ) {
      $opt->update();
    }
  }

}

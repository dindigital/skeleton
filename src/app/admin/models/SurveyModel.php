<?php

namespace src\app\admin\models;

use src\app\admin\validators\SurveyValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\SurveyQuestionModel;

/**
 *
 * @package app.models
 */
class SurveyModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('survey');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'title LIKE ?' => '%' . $arrFilters['title'] . '%',
        'is_del = ?' => '0'
    );

    $select = new Select('survey');
    $select->addField('id_survey');
    $select->addField('active');
    $select->addField('title');
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

    $sq = new SurveyQuestionModel;
    $row['question'] = $sq->getByIdSurvey($row['id_survey']);

    return $row;
  }

  public function getNew ()
  {
    $row = parent::getNew();
    $row['question'] = array();

    return $row;
  }

  public function insert ( $info )
  {
    //_# Valida o questionario container
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $info['active']);
    $this->setDefaultUri($info['title'], 'opine');
    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);
    $validator->setTotalQuestions(count($info['question']));


    //_# Valida e armazena as questões em array
    $arr_sq = array();

    foreach ( $info['question'] as $question ) {
      $sq = new SurveyQuestionModel;
      $sq->validate_insert(array(
          'id_survey' => $this->getId(),
          'question' => $question
      ));

      $arr_sq[] = $sq;
    }

    //_# Arremeça exceptions
    $validator->throwException();

    //_# Salva o questionário container
    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);

    //_# Salva as questões
    foreach ( $arr_sq as $sq ) {
      $sq->insert();
    }
  }

  public function update ( $info )
  {
    $this->setIntval('active', $info['active']);
    $this->setDefaultUri($info['title'], 'opine', $info['uri']);
    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);

    //_# Valida e armazena as questões em array
    $arr_sq = array();

    foreach ( $info['question'] as $question_id => $question ) {
      $sq = new SurveyQuestionModel;
      $sq->setId($question_id);
      $sq->validate_update(array(
          'question' => $question,
      ));

      $arr_sq[] = $sq;
    }

    //_# Arremeça exceptions
    $validator->throwException();

    //_# Salva o questionário container
    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_survey = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);

    //_# Salva as questões
    foreach ( $arr_sq as $sq ) {
      $sq->update();
    }
  }

  public function getListArray ()
  {
    $select = new Select('survey');
    $select->addField('id_survey');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_survey']] = $row['title'];
    }

    return $arrOptions;
  }

}

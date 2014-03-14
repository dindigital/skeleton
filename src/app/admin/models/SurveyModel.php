<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\SurveyQuestionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

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

  public function formatTable ( $table )
  {

    if ( is_null($table['id_survey']) ) {
      $table['question'] = array();
    } else {
      $sq = new SurveyQuestionModel;
      $table['question'] = $sq->getByIdSurvey($table['id_survey']);
    }

    $table['title'] = Html::scape($table['title']);
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'title LIKE ?' => '%' . $this->_filters['title'] . '%',
        'is_del = ?' => '0'
    );

    $select = new Select('survey');
    $select->addField('id_survey');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->where($arrCriteria);
    $select->order_by('inc_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
    }

    return $result;
  }

  public function insert ( $input )
  {
    //_# Valida o questionario container
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'opine');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');
    $validator->validateArrayNotEmpty('question', 'Questão');


    //_# Valida e armazena as questões em array
    $arr_sq = array();

    foreach ( $input['question'] as $question ) {
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
    $this->dao_insert();

    //_# Salva as questões
    foreach ( $arr_sq as $sq ) {
      $sq->insert();
    }
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'opine', $input['uri']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');

    //_# Valida e armazena as questões em array
    $arr_sq = array();

    foreach ( $input['question'] as $question_id => $question ) {
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
    $this->dao_update();

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

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

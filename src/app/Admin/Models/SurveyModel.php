<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Admin\Models\SurveyQuestionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use Din\Essential\Helpers\Link;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class SurveyModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('survey');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['uri'] = null;
    }

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
    $select->addField('is_active');
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
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->arrayNotEmpty()->validate('question', 'Pergunta');
    //
    $sq = new SurveyQuestionModel;
    $sq->batch_validate($input['question']);
    //
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_survey');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId(), 'opine')->filter('uri');
    //
    $this->dao_insert();

    $sq->batch_insert($this->getId(), $input['question']);

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->arrayNotEmpty()->validate('question', 'Pergunta');
    //
    $sq = new SurveyQuestionModel;
    $sq->batch_validate($input['question']);
    //
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId(), 'opine')->filter('uri');
    //
    $this->dao_update();

    $sq->batch_update($input['question']);

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

<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Helpers\PaginatorAdmin;
use Admin\Models\PollOptionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use Admin\Helpers\Link;
use Admin\Custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class PollModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('poll');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['uri'] = null;
    }

    if ( is_null($table['id_poll']) ) {
      $table['option'] = array();
    } else {
      $opt = new PollOptionModel;
      $table['option'] = $opt->getByIdPoll($table['id_poll']);
    }

    $table['question'] = Html::scape($table['question']);
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'question LIKE ?' => '%' . $this->_filters['question'] . '%',
        'is_del = ?' => '0'
    );

    $select = new Select('poll');
    $select->addField('id_poll');
    $select->addField('active');
    $select->addField('question');
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
    $v->string()->validate('question', 'Pergunta');
    $v->arrayNotEmpty()->validate('question', 'Pergunta');
    //
    $opt = new PollOptionModel;
    $opt->batch_validate($input['option']);
    //
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_poll');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('question');
    $f->defaultUri('question', $this->getId(), 'enquete')->filter('uri');
    //
    $this->dao_insert();

    $opt->batch_insert($this->getId(), $input['option']);

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('question', 'Pergunta');
    $v->arrayNotEmpty()->validate('question', 'Pergunta');
    //
    $opt = new PollOptionModel;
    $opt->batch_validate($input['option']);
    //
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('question');
    $f->defaultUri('question', $this->getId(), 'enquete')->filter('uri');
    //
    $this->dao_update();

    $opt->batch_update($input['option']);

  }

  public function formatFilters ()
  {
    $this->_filters['question'] = Html::scape($this->_filters['question']);

    return $this->_filters;

  }

}

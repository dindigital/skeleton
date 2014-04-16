<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class TagModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('tag');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      //
    }

    $table['title'] = Html::scape($table['title']);

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('tag');
    $select->addField('id_tag');
    $select->addField('is_active');
    $select->addField('title');
    $select->where($arrCriteria);
    $select->order_by('title');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->dbUnique($this->_dao, 'tag')->validate('title', 'Título');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_tag');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('title');

    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->dbUnique($this->_dao, 'tag', 'id_tag', $this->getId())->validate('title', 'Título');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->string()->filter('title');

    $this->dao_update();

  }

  public function short_insert ( $title )
  {

    $input = array(
        'is_active' => 1,
        'title' => $title
    );

    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->dbUnique($this->_dao, 'tag')->validate('title', 'Título');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_tag');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('title');

    $this->dao_insert();

  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;

  }

}

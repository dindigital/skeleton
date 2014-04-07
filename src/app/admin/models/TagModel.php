<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use src\app\admin\validators\DBValidator;
use src\app\admin\validators\StringValidator;
use Din\Exception\JsonException;

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
    $select->addField('active');
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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');

    $db_validator = new DBValidator($input, $this->_dao, 'tag');
    $db_validator->validateUniqueValue('title', 'Título');

    JsonException::throwException();

    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_tag');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('title');

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');

    $db_validator = new DBValidator($input, $this->_dao, 'tag');
    $db_validator->setId('id_tag', $this->getId());
    $db_validator->validateUniqueValue('title', 'Título');

    JsonException::throwException();

    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('title');

    $this->dao_update();
  }

  public function short_insert ( $title )
  {

    $input = array(
        'active' => 1,
        'title' => $title
    );

    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');

    $db_validator = new DBValidator($input, $this->_dao, 'tag');
    $db_validator->validateUniqueValue('title', 'Título');

    JsonException::throwException();

    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_tag');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('title');

    $this->dao_insert();
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

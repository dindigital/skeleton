<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use Admin\Custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class MailingGroupModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('mailing_group');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      //
    }

    $table['name'] = Html::scape($table['name']);

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $this->_filters['name'] . '%'
    );

    $select = new Select('mailing_group');
    $select->addField('id_mailing_group');
    $select->addField('name');
    $select->where($arrCriteria);
    $select->order_by('name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['name'] = Html::scape($row['name']);
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->dbUnique($this->_dao, 'mailing_group')->validate('name', 'Nome');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_mailing_group');
    $f->string()->filter('name');
    //
    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->dbUnique($this->_dao, 'mailing_group', 'id_mailing_group', $this->getId())->validate('name', 'Nome');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('name');
    //
    $this->dao_update();

  }

  public function short_insert ( $name )
  {
    $input = array(
        'name' => $name
    );

    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->dbUnique($this->_dao, 'mailing_group')->validate('name', 'Nome');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_mailing_group');
    $f->string()->filter('name');
    //
    $this->dao_insert();

  }

  public function getListArray ()
  {
    $select = new Select('mailing_group');
    $select->addField('id_mailing_group');
    $select->addField('name');

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_mailing_group']] = $row['name'];
    }

    return $arrOptions;

  }

  public function formatFilters ()
  {
    $this->_filters['name'] = Html::scape($this->_filters['name']);

    return $this->_filters;

  }

}

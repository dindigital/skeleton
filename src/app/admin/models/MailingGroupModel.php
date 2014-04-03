<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\DBValidator;
use Din\Exception\JsonException;
use src\app\admin\filters\TableFilter;

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

  public function formatTable ( $table, $excluded_fields = false )
  {
    if ( $excluded_fields ) {
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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'mailing_group');
    $db_validator->validateUniqueValue('name', 'Nome');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_mailing_group');
    $filter->setString('name');
    //
    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'mailing_group');
    $db_validator->setId('id_mailing_group', $this->getId());
    $db_validator->validateUniqueValue('name', 'Nome');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('name');
    //
    $this->dao_update();
  }

  public function short_insert ( $name )
  {
    $input = array(
        'name' => $name
    );

    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'mailing_group');
    $db_validator->validateUniqueValue('name', 'Nome');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_mailing_group');
    $filter->setString('name');
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

<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class LogModel extends BaseModelAdm
{

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'a.admin LIKE ?' => '%' . $arrFilters['admin'] . '%',
        'a.description LIKE ?' => '%' . $arrFilters['description'] . '%'
    );

    if ( $arrFilters['action'] != '0' && $arrFilters['action'] != '' ) {
      $arrCriteria['a.action = ?'] = $arrFilters['action'];
    }

    if ( $arrFilters['name'] != '0' && $arrFilters['name'] != '' ) {
      $arrCriteria['a.name = ?'] = $arrFilters['name'];
    }

    $select = new Select('log');
    $select->addField('id_log');
    $select->addField('admin');
    $select->addField('name');
    $select->addField('inc_date');
    $select->addField('action');
    $select->addField('description');
    $select->where($arrCriteria);
    $select->order_by('a.inc_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function getById ( $id )
  {
    $arrCriteria = array(
        'id_log = ?' => $id
    );

    $select = new Select('log');
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $result[0];

    return $row;
  }

  public function getDropdownName ()
  {
    $arrOptions = array();
    foreach ( Entities::$entities as $row ) {
      if ( isset($row['section']) && isset($row['tbl']) && isset($row['name']) )
        $arrOptions[$row['name']] = $row['section'];
    }

    return $arrOptions;
  }

}

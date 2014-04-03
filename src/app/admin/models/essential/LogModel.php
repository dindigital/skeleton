<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use Din\Filters\String\Html;

/**
 *
 * @package app.models
 */
class LogModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('log');
  }

  public function formatTable ( $table, $excluded_fields = false )
  {
    if ( $excluded_fields ) {
      //
    }

    $table['description'] = Html::scape($table['description']);
    $table['inc_date'] = DateFormat::filter_date($table['inc_date'], 'd/m/Y \à\s H:i:s');
    $table['action'] = Arrays::$logAcao[$table['action']];
    $table['cont'] = json_decode($table['content']);

    $entity = $this->_entities->getEntity($table['tbl']);
    $table['tbl'] = $entity->getSection();

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'a.admin LIKE ?' => '%' . $this->_filters['admin'] . '%',
        'a.description LIKE ?' => '%' . $this->_filters['description'] . '%'
    );

    if ( $this->_filters['action'] != '0' && $this->_filters['action'] != '' ) {
      $arrCriteria['a.action = ?'] = $this->_filters['action'];
    }

    if ( $this->_filters['tbl'] != '0' && $this->_filters['tbl'] != '' ) {
      $arrCriteria['a.tbl = ?'] = $this->_filters['tbl'];
    }

    //$arrCriteria['a.name IN (?)'] = array_keys($this->getDropdownName());

    $select = new Select('log');
    $select->addField('id_log');
    $select->addField('admin');
    $select->addField('tbl');
    $select->addField('inc_date');
    $select->addField('action');
    $select->addField('description');
    $select->where($arrCriteria);
    $select->order_by('a.inc_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['action'] = Arrays::$logAcao[$row['action']];
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date'], 'd/m/Y H:i:s');
      $entity = $this->_entities->getEntity($row['tbl']);
      $result[$i]['tbl'] = $entity->getSection();
    }

    return $result;
  }

  public function getById ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        'id_log = ?' => $this->getId()
    );

    $select = new Select('log');
    $select->addAllFields();
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $this->formatTable($result[0]);

    return $row;
  }

  public function formatFilters ()
  {
    $this->_filters['admin'] = Html::scape($this->_filters['admin']);
    $this->_filters['description'] = Html::scape($this->_filters['description']);
    $this->_filters['action'] = Form::Dropdown('action', Arrays::$logAcao, $this->_filters['action'], 'Filtro por Ação');
    $this->_filters['tbl'] = Form::Dropdown('tbl', $this->getDropdownName(), $this->_filters['tbl'], 'Filtro por Seção');

    return $this->_filters;
  }

  public function getDropdownName ()
  {
    $arrOptions = array();
    foreach ( $this->_entities->getSectionItens() as $entity ) {
      $entity_tbl = $entity->getTbl();
      $entity_section = $entity->getSection();

      $arrOptions[$entity_tbl] = $entity_section;
    }

    return $arrOptions;
  }

}

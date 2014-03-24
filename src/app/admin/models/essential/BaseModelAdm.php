<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use Din\File\Folder;
use Exception;
use src\app\admin\models\essential\LogMySQLModel as log;
use Din\DataAccessLayer\Table\Table;

class BaseModelAdm
{

  protected $_dao;
  protected $_paginator = null;
  protected $_itens_per_page = 20;
  protected $_table;
  protected $_filters;
  protected $_id;
  protected $_entities;
  public $_entity;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    $this->_entities = new Entities('config/entities.php');
  }

  /*
   * ===========================================================================
   * TABLE
   * ===========================================================================
   */

  public function setEntity ( $tablename )
  {
    $this->_entity = $this->_entities->getEntity($tablename);
    $this->setTable($tablename);
  }

  public function setTable ( $tablename )
  {
    $this->_table = new Table($tablename);
  }

  public function getTable ()
  {
    return $this->_table;
  }

  public function getIdName ()
  {
    $property = $this->_entity->getId();

    return $property;
  }

  public function getTableName ()
  {
    return $this->_table->getName();
  }

  public function setId ( $id )
  {
    $this->_table->{$this->getIdName()} = $id;
  }

  public function getId ()
  {
    return $this->_table->{$this->getIdName()};
  }

  /*
   * ===========================================================================
   * PAGED
   * ===========================================================================
   */

  public function setPaginationSelect ( $select )
  {
    $total = $this->_dao->select_count($select);
    $offset = $this->_paginator->getOffset($total);
    $select->setLimit($this->_itens_per_page, $offset);
  }

  public function getPaginator ()
  {
    return $this->_paginator;
  }

  /*
   * ===========================================================================
   * DATABASE CRUD
   * ===========================================================================
   */

  public function deleteChildren ( $tbl, $id )
  {
    $children = $this->_entity->getChildren();

    foreach ( $children as $child ) {
      $child = $this->_entities->getEntity($child);

      $select = new Select($child->getTbl());
      $select->addField($child->getId(), 'id_children');
      $select->where(array(
          $current['id'] . ' = ? ' => $id
      ));
      $result = $this->_dao->select($select);

      $arr_delete = array();
      foreach ( $result as $row ) {
        $arr_delete[] = array(
            'id' => $row['id_children']
        );
      }

      $model = $child->getModel();
      $child_model = new $model;
      $child_model->delete($arr_delete);
    }
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      //$current = Entities::getThis($this);
      $id = $this->_entity->getId();
      $tbl = $this->_entity->getTbl();
      $title = $this->_entity->getTitle();

      $tableHistory = $this->getById($item['id']);
      $this->deleteChildren($tbl, $item['id']);

      Folder::delete("public/system/uploads/{$tbl}/{$item['id']}");
      $this->_dao->delete($tbl, array($id . ' = ?' => $item['id']));
      if ( isset($title) ) {
        $this->log('D', $tableHistory[$title], $tbl, $tableHistory);
      }
    }
  }

  public function getById ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        $this->getIdName() . ' = ?' => $this->getId()
    );

    $select = new Select($this->getTableName());
    $select->addAllFields();
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $this->formatTable($result[0]);

    return $row;
  }

  public function getNew ()
  {
    $SQL = "DESCRIBE {$this->getTableName()}";

    $result = $this->_dao->execute($SQL, array(), true);

    $arr_return = array();

    foreach ( $result as $row ) {
      $arr_return[$row['Field']] = $row['Default'];
    }

    $table = $this->formatTable($arr_return);

    return $table;
  }

  protected function formatTable ( $table )
  {
    return $table;
  }

  public function save ( $info )
  {
    if ( !$this->getId() ) {
      $this->insert($info);
    } else {
      $this->update($info);
    }

    return $this->getId();
  }

  /**
    public function getCount ( $id )
    {
    $current = Entities::getThis($this);

    $arrCriteria = array(
    $current['id'] . ' = ?' => $id
    );

    $select = new Select($current['tbl']);
    $select->where($arrCriteria);

    $result = $this->_dao->select_count($select);

    return $result;
    }
   */
  protected function dao_insert ( $log = true )
  {
    $this->_dao->insert($this->_table);

    if ( $log ) {
      $this->log('C', $this->_table->{$this->_entity->getTitle()}, $this->_table);
    }
  }

  public function dao_update ( $log = true )
  {
    if ( $log ) {
      $tableHistory = $this->getById();
    }

    $this->_dao->update($this->_table, array("{$this->getIdName()} = ?" => $this->getId()));

    if ( $log ) {
      $this->log('U', $this->_table->{$this->_entity->getTitle()}, $this->_table, $tableHistory);
    }
  }

  /*
   * ===========================================================================
   * LOGGABLE
   * ===========================================================================
   */

  public function log ( $action, $msg, $table, $tableHistory = null, $entityname = null )
  {
    $adminAuth = new AdminAuthModel();
    $admin = $adminAuth->getUser();

    if ( is_null($entityname) ) {
      $entityname = $this->_entity->getTbl();
    }

    log::save($this->_dao, $admin, $action, $msg, $entityname, $table, $tableHistory);
  }

  public function setFilters ( Array $filters )
  {
    $this->_filters = $filters;
  }

}

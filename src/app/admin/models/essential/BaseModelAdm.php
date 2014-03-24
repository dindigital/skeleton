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

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    Entities::readFile('config/entities.php');
  }

  /*
   * ===========================================================================
   * TABLE
   * ===========================================================================
   */

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
    $entity = Entities::getThis($this);
    $property = $entity['id'];

    return $property;
  }

  public function getTableName ()
  {
    return $this->_table->getName();
  }

  public function setId ( $id )
  {
    $this->_id = $id;
  }

  public function getId ()
  {
    return $this->_id;
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
    $current = Entities::getEntity($tbl);
    $children = Entities::getChildren($tbl);

    foreach ( $children as $child ) {
      $select = new Select($child['tbl']);
      $select->addField($child['id'], 'id_children');
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

      $child_model = new $child['model'];
      $child_model->delete($arr_delete);
    }
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      $current = Entities::getThis($this);

      $tableHistory = $this->getById($item['id']);
      $this->deleteChildren($current['tbl'], $item['id']);

      Folder::delete("public/system/uploads/{$current['tbl']}/{$item['id']}");
      $this->_dao->delete($current['tbl'], array($current['id'] . ' = ?' => $item['id']));
      if ( isset($current['title']) ) {
        $this->log('D', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
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
      $current = Entities::getThis($this);
      $this->log('C', $this->_table->{$current['title']}, $this->_table);
    }
  }

  public function dao_update ( $log = true )
  {
    $current = Entities::getThis($this);

    if ( $log ) {
      $tableHistory = $this->getById();
    }

    $this->_dao->update($this->_table, array("{$this->getIdName()} = ?" => $this->getId()));

    if ( $log ) {
      $this->log('U', $this->_table->{$current['title']}, $this->_table, $tableHistory);
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
      $entities = Entities::getThis($this);
      $entityname = $entities['name'];
    }

    log::save($this->_dao, $admin, $action, $msg, $entityname, $table, $tableHistory);
  }

  public function setFilters ( Array $filters )
  {
    $this->_filters = $filters;
  }

}

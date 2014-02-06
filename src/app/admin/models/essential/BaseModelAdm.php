<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use Din\File\Folder;
use src\app\admin\helpers\Sequence;
use Exception;
use src\app\admin\models\essential\LogMySQLModel as log;
use Din\DataAccessLayer\Table\Table;

class BaseModelAdm
{

  protected $_dao;
  protected $_paginator = null;
  protected $_itens_per_page = 20;
  protected $_table;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    $this->setUpEntities();
  }

  public function setTable ( $tablename )
  {
    $this->_table = new Table($tablename);
  }

  public function setNewId ()
  {
    $this->setId(md5(uniqid()));
  }

  public function setId ( $id )
  {
    $entity = Entities::getThis($this);
    $property = $entity['id'];

    $this->_table->{$property} = $id;
  }

  public function getId ()
  {
    $entity = Entities::getThis($this);
    $property = $entity['id'];

    return $this->_table->{$property};
  }

  private function setUpEntities ()
  {
    Entities::readFile('config/entities.php');
  }

  public function setPaginationSelect ( $select )
  {
    $total = $this->_dao->select_count($select);
    $offset = $this->_paginator->getOffset($total);
    $select->setLimit($this->_itens_per_page, $offset);
  }

  public function getById ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $current = Entities::getThis($this);

    $arrCriteria = array(
        $current['id'] . ' = ?' => $this->getId()
    );

    $select = new Select($current['tbl']);
    $select->addField('*');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $result[0];

    return $row;
  }

  public function getNew ()
  {
    $current = Entities::getThis($this);

    $SQL = "DESCRIBE {$current['tbl']}";

    $result = $this->_dao->execute($SQL, array(), true);

    $arr_return = array();

    foreach ( $result as $row ) {
      $arr_return[$row['Field']] = $row['Default'];
    }

    return $arr_return;
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
      $this->log('D', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
    }
  }

  public function toggleActive ( $id, $active )
  {
    $current = Entities::getThis($this);

    $tableHistory = $this->getById($id);
    $validator = new $current['validator'](new Table($current['tbl']));
    $validator->setActive($active);
    $this->_dao->update($validator->getTable(), array($current['id'] . ' = ?' => $id));
    $this->log('U', $tableHistory[$current['title']], $validator->getTable(), $tableHistory);
  }

  public function operateSequence ( $operator, $arrCriteria )
  {
    $current = Entities::getThis($this);

    $SQL = "UPDATE {$current['tbl']} SET sequence = sequence {$operator} 1";
    $result = $this->_dao->execute($SQL, $arrCriteria);

    return $result;
  }

  public function updateSequence ( $sequence, $id )
  {
    $current = Entities::getThis($this);

    $validator = new $current['validator'](new Table($current['tbl']));
    $validator->setSequence($sequence);
    $this->_dao->update($validator->getTable(), array($current['id'] . '= ? ' => $id));
  }

  public function changeSequence ( $id, $sequence )
  {
    Sequence::changeSequence($this, $id, $sequence);
  }

  public function getMaxSequence ( $arrCriteria = array() )
  {
    $current = Entities::getThis($this);

    $select = new Select($current['tbl']);

    if ( $current['sequence']['optional'] ) {
      $arrCriteria['sequence > ?'] = '0';
    }

    if ( isset($current['trash']) ) {
      $arrCriteria['is_del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);
  }

  public function getPaginator ()
  {
    return $this->_paginator;
  }

}

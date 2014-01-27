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

class BaseModelAdm
{

  protected $_dao;
  protected $_paginator = null;
  protected $_itens_per_page = 20;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    $this->setUpEntities();
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

  public function getById ( $id )
  {
    $current = Entities::getThis($this);

    $arrCriteria = array(
        $current['id'] . ' = ?' => $id
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

  public function deleteChildren ( $tbl, $id, $permanent = false )
  {
    $current = Entities::getEntity($tbl);
    $children = Entities::getFilhos($tbl);

    foreach ( $children as $child ) {
      $select = new Select($child['tbl']);
      $select->addField($child['id'], 'id_children');
      $select->where(array(
          $current['id'] . ' = ? ' => $id
      ));
      $result = $this->_dao->select($select);

      foreach ( $result as $row ) {
        $model = new $child['model'];
        if ( $permanent ) {
          $model->delete_permanent($row['id_children']);
        } else {
          $model->delete($row['id_children']);
        }
      }
    }
  }

  public function log ( $action, $msg, $table, $tableHistory = null )
  {
    $adminAuth = new AdminAuthModel();
    $admin = $adminAuth->getUser();

    $entities = Entities::getThis($this);

    log::save($this->_dao, $admin, $action, $msg, $entities['name'], $table, $tableHistory);
  }

  public function delete ( $id )
  {
    $current = Entities::getThis($this);

    Sequence::changeSequence($this, $id, 0);

    $this->deleteChildren($current['tbl'], $id);
    $tableHistory = $this->getById($id);
    $validator = new $current['validator'];
    $validator->setDelDate();
    $validator->setIsDel('1');
    $this->_dao->update($validator->getTable(), array($current['id'] . ' = ?' => $id));
    $this->log('T', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
  }

  public function restore ( $id )
  {
    $current = Entities::getThis($this);

    $trash = new TrashModel();
    $trash->validateRestore($current['tbl'], $id);

    $tableHistory = $this->getById($id);

    $validator = new $current['validator'];

    Sequence::setSequence($this, $validator, $tableHistory);

    $validator->setIsDel('0');
    $this->_dao->update($validator->getTable(), array($current['id'] . ' = ?' => $id));
    $this->log('R', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
  }

  public function delete_permanent ( $id )
  {
    $current = Entities::getThis($this);

    $this->deleteChildren($current['tbl'], $id, true);

    $tableHistory = $this->getById($id);
    Folder::delete("public/system/uploads/{$current['tbl']}/{$id}");
    $this->_dao->delete($current['tbl'], array($current['id'] . ' = ?' => $id));
    $this->log('D', $tableHistory[$current['title']], $current['tbl'], $tableHistory);
  }

  public function toggleActive ( $id, $active )
  {
    $current = Entities::getThis($this);

    $tableHistory = $this->getById($id);
    $validator = new $current['validator'];
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

    $validator = new $current['validator'];
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

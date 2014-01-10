<?php

namespace src\app\admin\models;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use Din\DataAccessLayer\Entities;
use Din\DataAccessLayer\Select;
use Din\File\Folder;

class BaseModelAdm
{

  protected $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
    $this->setUpEntities();
  }

  private function setUpEntities ()
  {
    Entities::readFile('config/entities.php');
  }

  public function setPaginationSelect ( $select, $paginator )
  {
    $total = $this->_dao->select_count($select);
    $limit_offet = $paginator->getLimitOffset($total);
    $select->setLimit($limit_offet[0], $limit_offet[1]);
  }

  public function excluirFilhos ( $tbl, $id, $permanente = false )
  {
    $atual = Entities::getEntity($tbl);
    $filhos = Entities::getFilhos($tbl);

    foreach ( $filhos as $filho ) {
      $select = new Select($filho['tbl']);
      $select->addField($filho['id'], 'id_filho');
      $select->where(array(
          $atual['id'] . ' = ? ' => $id
      ));
      $result = $this->_dao->select($select);

      foreach ( $result as $row ) {
        $model_name = '\src\app\admin\models\\' . $filho['model'];
        $model_inst = new $model_name;
        if ( $permanente ) {
          $model_inst->excluir_permanente($row['id_filho']);
        } else {
          $model_inst->excluir($row['id_filho']);
        }
      }
    }
  }

  public function log ( $action, $msg, $table, $tableHistory = null )
  {
    $usuarioAuth = new UsuarioAuthModel();
    $usuario = $usuarioAuth->getUser();

    LogMySQLModel::save($this->_dao, $usuario, $action, $msg, $table, $tableHistory);
  }

  public function excluir ( $id )
  {
    $atual = Entities::getThis($this);

    $this->excluirFilhos($atual['tbl'], $id);
    $tableHistory = $this->getById($id);
    $validator_namespace = '\src\app\admin\validators\\' . $atual['validator'];
    $validator = new $validator_namespace;
    $validator->setDelData();
    $validator->setDel('1');
    $this->_dao->update($validator->getTable(), array($atual['id'] . ' = ?' => $id));
    $this->log('T', $tableHistory[$atual['title']], $atual['tbl'], $tableHistory);
  }

  public function restaurar ( $id )
  {
    $atual = Entities::getThis($this);

    $lixeira = new LixeiraModel();
    $lixeira->validateRestaurar($atual['tbl'], $id);

    $tableHistory = $this->getById($id);
    $validator_namespace = '\src\app\admin\validators\\' . $atual['validator'];
    $validator = new $validator_namespace;
    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array($atual['id'] . ' = ?' => $id));
    $this->log('R', $tableHistory[$atual['title']], $atual['tbl'], $tableHistory);
  }

  public function excluir_permanente ( $id )
  {
    $atual = Entities::getThis($this);

    $this->excluirFilhos($atual['tbl'], $id, true);

    $tableHistory = $this->getById($id);
    Folder::delete("public/system/uploads/{$atual['tbl']}/{$id}");
    $this->_dao->delete($atual['tbl'], array($atual['id'] . ' = ?' => $id));
    $this->log('D', $tableHistory[$atual['title']], $atual['tbl'], $tableHistory);
  }

  public function toggleAtivo ( $id, $ativo )
  {
    $atual = Entities::getThis($this);

    $tableHistory = $this->getById($id);
    $validator_namespace = '\src\app\admin\validators\\' . $atual['validator'];
    $validator = new $validator_namespace;
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array($atual['id'] . ' = ?' => $id));
    $this->log('U', $tableHistory[$atual['title']], $validator->getTable(), $tableHistory);
  }

}

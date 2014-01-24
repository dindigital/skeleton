<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use Din\File\Folder;
use src\app\admin\helpers\Ordem;
use Exception;
use src\app\admin\models\essential\LogMySQLModel as log;

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

  public function getById ( $id )
  {
    $atual = Entities::getThis($this);

    $arrCriteria = array(
        $atual['id'] . ' = ?' => $id
    );

    $select = new Select($atual['tbl']);
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
    $atual = Entities::getThis($this);

    $arrCriteria = array(
        $atual['id'] . ' = ?' => $id
    );

    $select = new Select($atual['tbl']);
    $select->where($arrCriteria);

    $result = $this->_dao->select_count($select);

    return $result;
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
        $model_inst = new $filho['model'];
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

    $entities = Entities::getThis($this);

    log::save($this->_dao, $usuario, $action, $msg, $entities['name'], $table, $tableHistory);
  }

  public function excluir ( $id )
  {
    $atual = Entities::getThis($this);

    Ordem::changeOrdem($this, $id, 0);

    $this->excluirFilhos($atual['tbl'], $id);
    $tableHistory = $this->getById($id);
    $validator = new $atual['validator'];
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

    $validator = new $atual['validator'];

    Ordem::setOrdem($this, $validator, $tableHistory);

    $validator->setDel('0');
    $this->_dao->update($validator->getTable(), array($atual['id'] . ' = ?' => $id));
    $this->log('R', $tableHistory[$atual['title']], $atual['tbl'], $tableHistory);
  }

  public function excluir_permanente ( $id )
  {
    $atual = Entities::getThis($this);

    Ordem::changeOrdem($this, $id, 0);

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
    $validator = new $atual['validator'];
    $validator->setAtivo($ativo);
    $this->_dao->update($validator->getTable(), array($atual['id'] . ' = ?' => $id));
    $this->log('U', $tableHistory[$atual['title']], $validator->getTable(), $tableHistory);
  }

  public function operaOrdem ( $operador, $arrCriteria )
  {
    $atual = Entities::getThis($this);

    $SQL = "UPDATE {$atual['tbl']} SET ordem = ordem {$operador} 1";
    $result = $this->_dao->execute($SQL, $arrCriteria);

    return $result;
  }

  public function atualizaOrdem ( $ordem, $id )
  {
    $atual = Entities::getThis($this);

    $validator = new $atual['validator'];
    $validator->setOrdem($ordem);
    $this->_dao->update($validator->getTable(), array($atual['id'] . '= ? ' => $id));
  }

  public function changeOrdem ( $id, $ordem )
  {
    Ordem::changeOrdem($this, $id, $ordem);
  }

  public function getMaxOrdem ( $arrCriteria = array() )
  {
    $atual = Entities::getThis($this);

    $select = new Select($atual['tbl']);

    if ( $atual['ordem']['opcional'] ) {
      $arrCriteria['ordem > ?'] = '0';
    }

    if ( isset($atual['lixeira']) ) {
      $arrCriteria['del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);
  }

}

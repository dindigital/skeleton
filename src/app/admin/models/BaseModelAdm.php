<?php

namespace src\app\admin\models;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;
use Din\DataAccessLayer\Entities;
use Din\DataAccessLayer\Select;

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

}

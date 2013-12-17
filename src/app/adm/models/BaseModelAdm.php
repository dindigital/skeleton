<?php

namespace src\app\adm\models;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;

class BaseModelAdm
{

  protected $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
  }

  public function setPaginationSelect ( $select, $paginator )
  {
    $total = $this->_dao->select_count($select);
    $limit_offet = $paginator->getLimitOffset($total);
    $select->setLimit($limit_offet[0], $limit_offet[1]);
  }

}

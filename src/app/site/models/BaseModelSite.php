<?php

namespace src\app\site\models;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;

class BaseModelSite
{

  protected $_dao;
  protected $_paginator;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));

  }

  public function setPaginationSelect ( $select, $itens )
  {
    $total = $this->_dao->select_count($select);
    $offset = $this->_paginator->getOffset($total);
    $select->setLimit($itens, $offset);

  }

  public function getPaginator ()
  {
    return $this->_paginator;

  }

}

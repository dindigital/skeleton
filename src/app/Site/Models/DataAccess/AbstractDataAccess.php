<?php

namespace Site\Models\DataAccess;

use Din\DataAccessLayer\PDO\PDOBuilder;
use Din\DataAccessLayer\DAO;

class AbstractDataAccess
{

  protected $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));

  }

}

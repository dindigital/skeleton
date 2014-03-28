<?php

namespace src\app\site\models;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\PDO\PDOBuilder;

class BaseModelSite
{

  protected $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(PDOBuilder::build(DB_TYPE, DB_HOST, DB_SCHEMA, DB_USER, DB_PASS));
  }
  
}
<?php

namespace Site\Models\DataAccess\Connection;

use Din\DataAccessLayer\DAO;
use Site\Models\DataAccess\Connection\DefaultMysql;

class AbstractDAOClient
{

  /**
   *
   * @var DAO
   */
  protected $_dao;

  public function __construct ()
  {
    $this->_dao = new DAO(DefaultMysql::getMysql()->getConnection());

  }

}

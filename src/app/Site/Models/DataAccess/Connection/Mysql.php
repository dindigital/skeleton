<?php

namespace Site\Models\DataAccess\Connection;

use Din\DataAccessLayer\PDO\PDOBuilder;

/**
 * Responsável por conectar no mysql através de uma extensão do PDO
 * Poderá retornar a conexão (PDODriver, extensão de \PDO)
 */
class Mysql
{

  protected $_connection;

  public function setConnection ( $host, $schema, $user, $pass )
  {
    $this->_connection = PDOBuilder::build('mysql', $host, $schema, $user, $pass);

  }

  /**
   *
   * @return \PDO
   */
  public function getConnection ()
  {
    return $this->_connection;

  }

}

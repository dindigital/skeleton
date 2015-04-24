<?php

namespace Site\Models\DataAccess\Connection;

use Site\Models\DataAccess\Connection\Mysql;

class DefaultMysql
{

  public static function getMysql ()
  {
    $mysql = new Mysql;
    $mysql->setConnection(DB_HOST, DB_SCHEMA, DB_USER, DB_PASS);

    return $mysql;

  }

}

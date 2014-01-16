<?php

namespace src\app\admin\models\essential;

interface LogInterface
{

  public static function save ( $dao, $usuario, $action, $msg, $table, $tableHistory );

  public function insert ( $table_name );

  public function update ( $table_name );

  public function deleteRestore ( $table_name, $action );
}

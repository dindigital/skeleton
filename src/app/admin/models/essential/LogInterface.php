<?php

namespace src\app\admin\models\essential;

interface LogInterface
{

  public static function save ( $dao, $usuario, $action, $msg, $name, $table, $tableHistory );

  public function insert ();

  public function update ();

  public function deleteRestore ( $action );
}

<?php

namespace Admin\Models\Essential;

interface LogInterface
{

  public static function save ( $dao, $admin, $action, $msg, $table, $tableHistory );

  public function insert ();

  public function update ();

  public function deleteRestore ( $action );
}

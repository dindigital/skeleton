<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\Table\iTable;

abstract class LogAbstract
{

  protected $_dao;
  protected $usuario;
  protected $msg;
  protected $table;
  protected $tableHistory;

  public function logicSave ( $action )
  {
    $table_name = $this->tableName();
    switch ($action) {
      case 'C':
        $this->insert($table_name);
        break;
      case 'U':
        $this->update($table_name);
        break;
      case 'D':
      case 'T':
      case 'R':
        $this->deleteRestore($table_name, $action);
        break;
    }
  }

  private function tableName ()
  {
    $table = $this->table;
    if ( is_object($table) && $table instanceof iTable ) {
      $table = $table->getName();
    }
    return $table;
  }

}

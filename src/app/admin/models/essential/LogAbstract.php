<?php

namespace src\app\admin\models\essential;

abstract class LogAbstract
{

  protected $_dao;
  protected $admin;
  protected $msg;
  protected $name;
  protected $table;
  protected $tableHistory;

  public function logicSave ( $action )
  {
    switch ($action) {
      case 'C':
        $this->insert();
        break;
      case 'U':
        $this->update();
        break;
      case 'D':
      case 'T':
      case 'R':
        $this->deleteRestore($action);
        break;
    }
  }

}

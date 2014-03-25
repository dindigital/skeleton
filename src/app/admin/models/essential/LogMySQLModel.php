<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\LogAbstract;
use Din\DataAccessLayer\Table\Table;

class LogMySQLModel extends LogAbstract
{

  protected $_table;

  public function __construct ()
  {
    $this->_table = new Table('log');
  }

  public static function save ( $dao, $admin, $action, $msg, Table $table, $tableHistory )
  {
    $log = new self;
    $log->_dao = $dao;
    $log->admin = $admin;
    $log->msg = $msg;
    $log->table = $table;
    $log->tableHistory = $tableHistory;

    $log->logicSave($action);
  }

  public function insert ()
  {
    $this->_table->admin = $this->admin['name'];
    $this->_table->tbl = $this->table->getName();
    $this->_table->action = 'C';
    $this->_table->description = $this->msg;
    $this->_table->content = json_encode($this->table->setted_values);
    $this->_table->inc_date = date('Y-m-d H:i:s');

    $this->_dao->insert($this->_table);
  }

  public function update ()
  {

    $diff = array();

    foreach ( $this->table->setted_values as $key => $value ) {
      if ( $value != $this->tableHistory[$key] ) {
        $diff[$key] = array(
            'before' => $this->tableHistory[$key],
            'after' => $value
        );
      }
    }

    if ( count($diff) ) {
      $content = json_encode($diff);
    } else {
      $content = 'Não houveram alterações';
    }

    $this->_table->admin = $this->admin['name'];
    $this->_table->tbl = $this->table->getName();
    $this->_table->action = 'U';
    $this->_table->description = $this->msg;
    $this->_table->content = $content;
    $this->_table->inc_date = date('Y-m-d H:i:s');

    $this->_dao->insert($this->_table);
  }

  public function deleteRestore ( $action )
  {
    $this->_table->admin = $this->admin['name'];
    $this->_table->tbl = $this->table->getName();
    $this->_table->action = $action;
    $this->_table->description = $this->msg;
    $this->_table->content = json_encode($this->tableHistory);
    $this->_table->inc_date = date('Y-m-d H:i:s');

    $this->_dao->insert($this->_table);
  }

}

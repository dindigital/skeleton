<?php

namespace Site\Models\DataAccess\Save;

use Din\DataAccessLayer\Table\Table;
use Site\Models\DataAccess\Connection\AbstractDAOClient;

abstract class AbstractSave extends AbstractDAOClient
{

  protected $_table;
  protected $_entity;

  public function __construct($table_name) {
    parent::__construct();
    $this->_table = new Table($table_name);
  }

  public function setEntity($entity) {
    $this->_entity = $entity;
  }

  public function getNewId() {
    return md5(uniqid());
  }

  public function setIncDate() {
    $this->_table->inc_date = date('Y-m-d H:i:s');
  }

  public function setIp() {
    $this->_table->ip = $_SERVER['REMOTE_ADDR'];
  }

  public function save ()
  {
    return $this->_dao->insert($this->_table);
  }

}

<?php

namespace Site\Models\DataAccess\Save;

use Din\DataAccessLayer\Table\Table;
use Site\Models\DataAccess\Connection\AbstractDAOClient;

class AbstractSave extends AbstractDAOClient
{

  protected $_table;

  public function __construct($table_name) {
    parent::__construct();
    $this->_table = new Table($table_name);
  }

  public function getNewId() {
    return md5(uniqid());
  }

  public function setIncDate() {
    $this->_table->inc_date = date('Y-m-d H:i:s');
  }

  public function save ()
  {
   $this->_dao->insert($this->_table);
  }

}

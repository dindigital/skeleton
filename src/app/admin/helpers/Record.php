<?php

namespace src\app\admin\helpers;

use Din\DataAccessLayer\Select;
use Din\DataAccessLayer\DAO;

class Record
{

  protected $_dao;
  protected $_table;
  protected $_criteria;

  public function __construct ( DAO $dao )
  {
    $this->_dao = $dao;
  }

  public function setTable ( $table )
  {
    $this->_table = $table;
  }

  public function setCriteria ( array $arrCriteria )
  {
    $this->_criteria = $arrCriteria;
  }

  public function exists ()
  {
    $select = new Select($this->_table);
    $select->where($this->_criteria);
    $count = $this->_dao->select_count($select);

    return $count > 0;
  }

}

<?php

namespace src\app\admin\helpers;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\RelationshipValidator;

class Relationship
{

  private $_dao;

  function __construct ( DAO $dao )
  {
    $this->_dao = $dao;
  }

}


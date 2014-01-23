<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;

class RelationshipValidator extends BaseValidator
{

  public function __construct ( $tbl )
  {
    $this->_table = new Table($tbl);
  }

  public function __set ( $name, $value )
  {
    $this->_table->$name = $value;
  }

}

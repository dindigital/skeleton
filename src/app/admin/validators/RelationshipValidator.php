<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;

class RelationshipValidator extends BaseValidator
{

  public function __set ( $name, $value )
  {
    $this->_table->$name = $value;
  }

}

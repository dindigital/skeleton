<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;

class MailingGroupValidator extends BaseValidator
{

  public function setName ( $name )
  {
    if ( $name == '' )
      return JsonException::addException('Nome é obrigatório');

    $this->_table->name = $name;
  }

}

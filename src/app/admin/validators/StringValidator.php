<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class StringValidator extends BaseValidator2
{

  public function validateRequiredString ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->notEmpty()->validate($value) )
      return JsonException::addException("O campo {$label} é de preenchimento obrigatório");
  }

}

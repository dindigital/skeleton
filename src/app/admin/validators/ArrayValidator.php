<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;

class ArrayValidator extends BaseValidator
{

  public function validateArrayNotEmpty ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( count($value) == 0 )
      return JsonException::addException("É necessáio pelo menos 1 {$label}");
  }

}

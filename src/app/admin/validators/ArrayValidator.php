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

  public function validateArrayExists ( $prop, $array )
  {
    $value = $this->getValue($prop);

    if (!in_array($value, $array) )
      return JsonException::addException("Item {$value} não encontrado no array de opções");
  }

  public function validateArrayKeyExists ( $prop, $array )
  {
    $value = $this->getValue($prop);

    if (!array_key_exists($value, $array) )
      return JsonException::addException("Item {$value} não encontrado nas chaves do array de opções");
  }

}

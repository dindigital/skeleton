<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;

class BaseValidator
{

  protected $_input = array();

  public function __construct ( $input )
  {
    $this->setInput($input);
  }

  protected function setInput ( $input )
  {
    $this->_input = $input;
  }

  protected function getValue ( $prop )
  {
    if ( !array_key_exists($prop, $this->_input) )
      return JsonException::addException("Índice {$prop} não existe no array de input do validator");

    return $this->_input[$prop];
  }

  public function throwException ()
  {
    JsonException::throwException();
  }

}

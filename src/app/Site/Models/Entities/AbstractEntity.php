<?php

namespace Site\Models\Entities;

abstract class AbstractEntity
{

  protected $_row;

  public function __set ( $name, $value )
  {
    $this->_row[$name] = $value;

  }

  public function __call ( $name, $arguments )
  {
    if ( strpos($name, 'get') === 0 ) {
      $camelCase = substr($name, 3);
      $underscore = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $camelCase));

      if ( array_key_exists($underscore, $this->_row) ) {
        return $this->_row[$underscore];
      }
    }

    throw new \BadFunctionCallException("Method {$name} does not exist on " . get_called_class());

  }

  public function getRow ()
  {
    return $this->_row;

  }

  public function setRow ( $row )
  {
    $this->_row = $row;

  }

}

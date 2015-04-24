<?php

namespace Site\Models\DataAccess\Entity;

abstract class AbstractEntity
{

  protected $_fields = array();

  public function setField ( $k, $v )
  {
    $this->_fields[$k] = $v;

    return $this;

  }

  /**
   *
   * @param string $k
   * @return mixed
   * @throws Exception\UnknownFieldException
   */
  public function getField ( $k )
  {
    if ( !array_key_exists($k, $this->_fields) )
      throw new Exception\UnknownFieldException('Unknown field: ' . $k);

    return $this->_fields[$k];

  }

  public function getFields ()
  {
    return $this->_fields;

  }

  public function setFields ( $array )
  {
    $this->_fields = $array;

    return $this;

  }

  public function __set ( $name, $value )
  {
    $this->setField($name, $value);

  }

}

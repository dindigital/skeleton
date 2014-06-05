<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\AbstractEntity;

abstract class AbstractDecorator extends AbstractEntity
{

  protected $_entity;

  public function __construct ( AbstractEntity $entity )
  {
    $this->_entity = $entity;

  }

  public function __set ( $name, $value )
  {
    $this->_entity->{$name} = $value;

  }

  public function __call ( $name, $arguments )
  {
    return call_user_func_array(array($this->_entity, $name), $arguments);

  }

  public function getEntity ()
  {
    return $this->_entity;

  }

}

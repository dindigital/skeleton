<?php

namespace Site\Models\DataAccess\Entity\Decorators;

abstract class AbstractDecorator
{

  protected $_entity;

  public function __construct ( $entity )
  {
    $this->_entity = $entity;

  }

  public function __call ( $name, $arguments )
  {

    try {
      return call_user_func_array(array($this->_entity, $name), $arguments);
    } catch (\ErrorException $e) {
      $entity_name = get_class($this->_entity);
      $debug = debug_backtrace();
      $file = $debug[0]['file'];
      $line = $debug[0]['line'];

      throw new \ErrorException("Method {$name} does not exists on {$entity_name}. "
      . "Called in {$file} Line {$line}");
    }

  }

}

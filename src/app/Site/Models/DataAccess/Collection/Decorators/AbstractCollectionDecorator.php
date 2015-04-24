<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\AbstractCollection;

abstract class AbstractCollectionDecorator implements \Iterator, \Countable
{

  protected $_collection;

  public function __construct ( AbstractCollection $collection )
  {
    $this->_collection = $collection;

  }

  function rewind ()
  {
    return $this->_collection->rewind();

  }

  function current ()
  {
    return $this->_collection->current();

  }

  function key ()
  {
    return $this->_collection->key();

  }

  function next ()
  {
    return $this->_collection->next();

  }

  function valid ()
  {
    return $this->_collection->valid();

  }

  public function getCollection ()
  {
    return $this->_collection;

  }

  public function count ()
  {
    return count($this->_collection);

  }

  public function __call ( $name, $arguments )
  {
    return call_user_func_array(array($this->_collection, $name), $arguments);

  }

}

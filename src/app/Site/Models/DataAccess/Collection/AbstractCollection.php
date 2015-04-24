<?php

namespace Site\Models\DataAccess\Collection;

abstract class AbstractCollection implements \Iterator, \Countable
{

  private $_position = 0;
  private $_itens = array();

  function rewind ()
  {
    $this->_position = 0;

  }

  function current ()
  {
    return $this->_itens[$this->_position];

  }

  function key ()
  {
    return $this->_position;

  }

  function next ()
  {
    ++$this->_position;

  }

  function valid ()
  {
    return isset($this->_itens[$this->_position]);

  }

  public function count ()
  {
    return count($this->_itens);

  }

  public function setItens ( $itens )
  {
    $this->_itens = $itens;

  }

}

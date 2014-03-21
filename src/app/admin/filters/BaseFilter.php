<?php

namespace src\app\admin\filters;

use Din\DataAccessLayer\Table\Table;

class BaseFilter
{

  protected $_table;
  protected $_input;

  public function __construct ( Table $table, array $input )
  {
    $this->setTable($table);
    $this->setInput($input);
  }

  protected function setTable ( Table $table )
  {
    $this->_table = $table;
  }

  protected function setInput ( array $input )
  {
    $this->_input = $input;
  }

  protected function getValue ( $field )
  {
    if ( !array_key_exists($field, $this->_input) )
      return JsonException::addException("Índice {$field} não existe no array de input do filter");

    return $this->_input[$field];
  }

}

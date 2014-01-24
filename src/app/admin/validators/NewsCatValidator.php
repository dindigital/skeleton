<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;

class NewsCatValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('news_cat');
  }

  public function setTitle ( $title )
  {
    if ( $title == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->title = $title;
  }

  public function setIsHome ( $is_home )
  {
    $is_home = intval($is_home);

    $this->_table->is_home = $is_home;
  }

}

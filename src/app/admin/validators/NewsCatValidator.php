<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class NewsCatValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('news_cat');
  }

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($title) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

    $this->_table->title = $title;
  }

  public function setIsHome ( $is_home )
  {
    $is_home = intval($is_home);

    $this->_table->is_home = $is_home;
  }

}

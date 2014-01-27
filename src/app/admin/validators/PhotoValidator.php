<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Din\Filters\Date\DateToSql;
use Respect\Validation\Validator as v;

class PhotoValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('photo');
  }

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($title) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

    $this->_table->title = $title;
  }

  public function setDate ( $date )
  {
    if ( !DateToSql::validate($date) )
      return JsonException::addException('Data é obrigatório');

    $date = DateToSql::filter_date($date);

    $this->_table->date = $date;
  }

}

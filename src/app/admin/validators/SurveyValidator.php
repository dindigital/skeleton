<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class SurveyValidator extends BaseValidator
{

  public function setTitle ( $title )
  {
    if ( !v::string()->notEmpty()->validate($title) )
      return JsonException::addException('Titulo é obrigatório');

    if ( !v::string()->length(1, 255)->validate($title) )
      return JsonException::addException('Titulo pode ter no máximo 255 caracteres.');

    $this->_table->title = $title;
  }

  public function setTotalQuestions ( $count )
  {
    if ( $count == 0 )
      return JsonException::addException('É necessário ter no mínimo 1 questão');
  }

}

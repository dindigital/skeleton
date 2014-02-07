<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class PollValidator extends BaseValidator
{

  public function setQuestion ( $question )
  {
    if ( !v::string()->notEmpty()->validate($question) )
      return JsonException::addException('Pergunta é obrigatório');

    if ( !v::string()->length(1, 255)->validate($question) )
      return JsonException::addException('Pergunta pode ter no máximo 255 caracteres.');

    $this->_table->question = $question;
  }

  public function setTotalOptions ( $count )
  {
    if ( $count == 0 )
      return JsonException::addException('É necessário ter no mínimo 1 alternativa');
  }

}

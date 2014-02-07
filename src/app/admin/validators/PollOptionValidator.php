<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;

class PollOptionValidator extends BaseValidator
{

  public function setIdPoll ( $id_poll )
  {
    $this->_table->id_poll = $id_poll;
  }

  public function setOption ( $option )
  {
    if ( !v::string()->notEmpty()->validate($option) )
      return JsonException::addException('Campo alternativa não pode ficar em branco');

    $this->_table->option = $option;
  }

}

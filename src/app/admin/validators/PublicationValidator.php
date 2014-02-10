<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;

class PublicationValidator extends BaseValidator
{

  public function setTitle ( $title )
  {
    if ( $title == '' )
      return JsonException::addException('Titulo é obrigatório');

    $this->_table->title = $title;
  }

}

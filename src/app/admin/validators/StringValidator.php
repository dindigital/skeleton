<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use Respect\Validation\Validator as v;
use Din\Filters\Date\DateToSql;

class StringValidator extends BaseValidator2
{

  public function validateRequiredString ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->notEmpty()->validate($value) )
      return JsonException::addException("O campo {$label} é de preenchimento obrigatório");
  }

  public function validateRequiredEmail ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::email()->validate($value) )
      return JsonException::addException("O campo {$label} não contém um e-mail válido");
  }

  public function validateRequiredDate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !DateToSql::validate($value) )
      return JsonException::addException("O campo {$label} não contém uma data válida");
  }

}

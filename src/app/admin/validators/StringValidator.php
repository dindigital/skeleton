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

  public function validateEqualValues ( $prop1, $prop2, $label )
  {
    $value1 = $this->getValue($prop1);
    $value2 = $this->getValue($prop2);

    if ( $value1 != $value2 )
      return JsonException::addException("Os campos de {$label} devem conter o mesmo valor");
  }

  /*
    public function setMinMaxString ( $prop, $label, $min = 1, $max = null )
    {
    $value = $this->getValue($prop);

    $message = "O campo {$label} precisa ter no mínimo {$min} caracteres";
    if ( !is_null($max) )
    $message .= " e no máximo {$max} caracteres";

    if ( !v::string()->length($min, $max)->validate($value) )
    return JsonException::addException($message);

    $this->_table->{$prop} = $value;
    }

    public function setLenghtString ( $prop, $label, $lenght = 1 )
    {
    $value = $this->getValue($prop);

    if ( !v::string()->length($lenght, $lenght)->validate($value) )
    return JsonException::addException("O campo {$label} precisa ter {$lenght} caracteres");

    $this->_table->{$prop} = $value;
    }
    public function setRequiredMoney ( $prop, $label )
    {
    $value = $this->getValue($prop);
    $value = Money::filter_sql($value);

    if ( !v::numeric()->validate($value) )
    return JsonException::addException("O campo {$label} é de preenchimento obrigatório");

    $this->_table->{$prop} = $value;
    }

   */
}

<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use Din\DataAccessLayer\Select;

class AdminPasswordValidator extends BaseValidator
{

  public function validateToken ( $id )
  {
    $select = new Select('admin');
    $select->addField('password_change_date');
    $select->where(array('id_admin = ?' => $id));
    $result = $this->_dao->select($select);

    if ( !count($result) )
      return JsonException::addException('Token inválido, por favor gere um novo link de recuperação');

    $time_inicial = strtotime(date('Y-m-d'));
    $time_final = strtotime($result[0]['password_change_date']);

    $diferenca = $time_final - $time_inicial;
    $dias = (int) floor($diferenca / (60 * 60 * 24));

    if ( $dias !== 0 )
      return JsonException::addException('Token expirado, por favor gere um novo link de recuperação');
  }

}

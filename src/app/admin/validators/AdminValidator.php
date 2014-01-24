<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Respect\Validation\Validator as v;
use Din\Crypt\Crypt;
use Din\Exception\JsonException;

class AdminValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('admin');
  }

  public function setName ( $name )
  {
    if ( !v::string()->notEmpty()->validate($name) )
      return JsonException::addException('Nome é obrigatório');

    $this->_table->name = $name;
  }

  public function setEmail ( $email, $id = null )
  {
    if ( !v::email()->validate($email) )
      return JsonException::addException('E-mail inválido');

// TO DO: Verificar se usuário existe no banco de dados, se existir
// não deixar usar o mesmo e-mail

    $this->_table->email = $email;
  }

  public function setPassword ( $password, $required = true )
  {
    if ( !v::string()->notEmpty()->validate($password) && $required )
      return JsonException::addException('Senha é obrigatório');

    if ( $password != '' ) {
      $crypt = new Crypt();
      $this->_table->password = $crypt->crypt($password);
    }
  }

  public function setPermission ( $permission )
  {
    $permission = json_encode($permission);
    $this->_table->permission = $permission;
  }

}

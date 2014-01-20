<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Respect\Validation\Validator as v;
use Din\Crypt\Crypt;
use Din\Exception\JsonException;

class UsuarioValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new Table('usuario');
  }

  public function setNome ( $nome )
  {
    if ( !v::string()->notEmpty()->validate($nome) )
      return JsonException::addException('Nome é obrigatório');

    $this->_table->nome = $nome;
  }

  public function setEmail ( $email, $id = null )
  {
    if ( !v::email()->validate($email) )
      return JsonException::addException('E-mail inválido');

    // TO DO: Verificar se usuário existe no banco de dados, se existir
    // não deixar usar o mesmo e-mail

    $this->_table->email = $email;
  }

  public function setSenha ( $senha, $obg = true )
  {
    if ( !v::string()->notEmpty()->validate($senha) && $obg )
      return JsonException::addException('Senha é obrigatório');

    if ( $senha != '' ) {
      $crypt = new Crypt();
      $this->_table->senha = $crypt->crypt($senha);
    }
  }

  public function setPermissao ( $permissao )
  {
    $permissao = json_encode($permissao);
    $this->_table->permissao = $permissao;
  }

}

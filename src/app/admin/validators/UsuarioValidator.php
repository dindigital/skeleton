<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\UsuarioTable;
use Respect\Validation\Validator as v;
use Din\Crypt\Crypt;
use Din\Exception\JsonException;

class UsuarioValidator extends BaseValidator
{

  public function __construct ()
  {
    $this->_table = new UsuarioTable();
  }

  public function setIdUsuario ()
  {
    $this->_table->id_usuario = $this->_table->getNewId();

    return $this;
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

    $SQL = "SELECT * FROM usuario {\$strWhere}";
    $arrCriteria = array();
    $arrCriteria['email'] = $email;
    if ( $id ) {
      $arrCriteria['id_usuario'] = array('<>' => $id);
    }

//    $result = $this->_dao->getByCriteria($this->_table, $SQL, $arrCriteria);
//
//    if ( count($result) )
//      throw new \Exception('Este e-mail já existe.');

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

}

<?php

namespace src\app\adm\validators;

use src\app\adm\validators\BaseValidator;
use src\tables\UsuarioTable;
use Din\Validation\Validate;
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
    if ( $nome == '' )
      return JsonException::addException(array('nome' => 'Nome é obrigatório'));

    $this->_table->nome = $nome;
  }

  public function setEmail ( $email, $id = null )
  {
    if ( $email == '' || !Validate::email($email) )
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
    if ( $senha == '' && $obg )
      return JsonException::addException('Senha é obrigatório');

    if ( $senha != '' ) {
      $crypt = new Crypt();
      $this->_table->senha = $crypt->crypt($senha);
    }
  }

}

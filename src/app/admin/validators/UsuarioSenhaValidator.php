<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\UsuarioTable;
use Respect\Validation\Validator as v;
use Din\Exception\JsonException;
use Din\DataAccessLayer\Select;
use Din\Crypt\Crypt;

class UsuarioSenhaValidator extends BaseValidator
{

  private $_dao;

  public function __construct ( $dao )
  {
    $this->_table = new UsuarioTable();
    $this->_dao = $dao;
  }

  public function setEmail ( $email )
  {
    if ( !v::email()->validate($email) )
      return JsonException::addException('E-mail inválido');

    $select = new Select('usuario');
    $select->where(array('email = ?' => $email));
    $result = $this->_dao->select_count($select);

    if ( !$result )
      return JsonException::addException('E-mail não encontrado');

    $this->_table->email = $email;
  }

  public function setToken ( $id )
  {
    $select = new Select('usuario');
    $select->addField('senha_data');
    $select->where(array('id_usuario = ?' => $id));
    $result = $this->_dao->select($select);

    if ( !count($result) )
      return JsonException::addException('Token inválido, por favor gere um novo link de recuperação');

    $time_inicial = strtotime(date('Y-m-d'));
    $time_final = strtotime($result[0]['senha_data']);

    $diferenca = $time_final - $time_inicial;
    $dias = (int) floor($diferenca / (60 * 60 * 24));

    if ( $dias !== 0 )
      return JsonException::addException('Token expirado, por favor gere um novo link de recuperação');
  }

  public function setSenha ( $senha, $senha2 )
  {
    if ( $senha != $senha2 )
      return JsonException::addException('As senhas não conferem');

    if ( !v::string()->notEmpty()->validate($senha) )
      return JsonException::addException('Por favor digite uma senha');

    $crypt = new Crypt();
    $this->_table->senha = $crypt->crypt($senha);
  }

  public function setSenhaData ( $data )
  {
    $this->_table->senha_data = $data;
  }

}

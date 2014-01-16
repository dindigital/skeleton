<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\Select;
use src\app\admin\validators\UsuarioSenhaValidator;

/**
 *
 * @package app.models
 */
class UsuarioSenhaModel extends BaseModelAdm
{

  private $_token = null;

  public function recuperar_senha ( $email )
  {
    $validator = new UsuarioSenhaValidator($this->_dao);
    $validator->setEmail($email);
    $validator->setSenhaData(date('Y-m-d'));
    $validator->throwException();

    // Atualiza o campo senha_data para a data atual, com isso conseguiremos posteriormente
    // verificar se a recuperação de senha ainda é válida
    $this->_dao->update($validator->getTable(), array('email = ?' => $email));

    $this->setToken($email);
  }

  private function setToken ( $email )
  {
    // Retorno o ID do banco de dados
    $select = new Select('usuario');
    $select->addField('id_usuario');
    $select->where(array('email = ?' => $email));
    $result = $this->_dao->select($select);

    $this->_token = $result[0]['id_usuario'];
  }

  public function getToken ()
  {
    return $this->_token;
  }

  public function atualiza_senha ( $data )
  {
    $validator = new UsuarioSenhaValidator($this->_dao);
    $validator->setToken($data['token']);
    $validator->throwException();
    $validator->setSenha($data['senha'], $data['senha2']);
    $validator->setSenhaData(null);
    $validator->throwException();

    $this->_dao->update($validator->getTable(), array('id_usuario = ?' => $data['token']));
  }

}

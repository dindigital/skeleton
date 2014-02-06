<?php

namespace src\app\admin\models\essential;

use Din\DataAccessLayer\Select;
use src\app\admin\validators\AdminPasswordValidator as validator;

/**
 *
 * @package app.models
 */
class AdminPasswordModel extends BaseModelAdm
{

  private $_token = null;

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('admin');
  }

  public function recover_password ( $email )
  {
    $validator = new validator($this->_table, $this->_dao);
    $validator->setEmail($email);
    $validator->setPasswordChangeDate(date('Y-m-d'));
    $validator->throwException();

    // Atualiza o campo senha_data para a data atual, com isso conseguiremos posteriormente
    // verificar se a recuperação de senha ainda é válida
    $this->_dao->update($this->_table, array('email = ?' => $email));

    $this->setToken($email);
  }

  private function setToken ( $email )
  {
    // Retorno o ID do banco de dados
    $select = new Select('admin');
    $select->addField('id_admin');
    $select->where(array('email = ?' => $email));
    $result = $this->_dao->select($select);

    $this->_token = $result[0]['id_admin'];
  }

  public function getToken ()
  {
    return $this->_token;
  }

  public function update_password ( $data )
  {
    $validator = new validator($this->_table, $this->_dao);
    $validator->setToken($data['token']);
    $validator->setPassword($data['password'], $data['password2']);
    $validator->setPasswordChangeDate(null);
    $validator->throwException();

    $this->_dao->update($this->_table, array('id_admin = ?' => $data['token']));
  }

}

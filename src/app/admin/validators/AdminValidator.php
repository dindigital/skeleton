<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Respect\Validation\Validator as v;
use Din\Crypt\Crypt;
use Din\Exception\JsonException;
use src\app\admin\helpers\Record;
use Din\DataAccessLayer\Table\iTable;
use Din\DataAccessLayer\DAO;

class AdminValidator extends BaseValidator
{

  protected $_dao;

  public function __construct ( iTable $table, DAO $dao )
  {
    parent::__construct($table);
    $this->_dao = $dao;
  }

  public function setName ( $name )
  {
    if ( !v::string()->notEmpty()->validate($name) )
      return JsonException::addException('Nome é obrigatório');

    $this->_table->name = $name;
  }

  public function setEmail ( $email, $ignore_id = null )
  {
    if ( !v::email()->validate($email) )
      return JsonException::addException('E-mail inválido');

    $arrCriteria = array(
        'email = ?' => $email
    );

    if ( $ignore_id ) {
      $arrCriteria['id_admin <> ?'] = $ignore_id;
    }

    $record = new Record($this->_dao);
    $record->setTable('admin');
    $record->setCriteria($arrCriteria);
    if ( $record->exists() )
      return JsonException::addException('Já existe um registro com este e-mail');

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

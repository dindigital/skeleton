<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\Exception\JsonException;
use Respect\Validation\Validator as v;
use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Table\iTable;
use src\app\admin\helpers\Record;

class MailingValidator extends BaseValidator
{

  protected $_dao;

  public function __construct ( iTable $table, DAO $dao )
  {
    parent::__construct($table);
    $this->_dao = $dao;
  }

  public function setName ( $name )
  {
    if ( $name == '' )
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
      $arrCriteria['id_mailing <> ?'] = $ignore_id;
    }

    $record = new Record($this->_dao);
    $record->setTable('mailing');
    $record->setCriteria($arrCriteria);
    if ( $record->exists() )
      return JsonException::addException('Já existe um registro com este e-mail');

    $this->_table->email = $email;
  }

}

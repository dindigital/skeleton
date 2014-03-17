<?php

namespace src\app\admin\validators;

use Din\Exception\JsonException;
use Din\DataAccessLayer\DAO;
use src\app\admin\helpers\Record;

class DBValidator extends BaseValidator2
{

  protected $_dao;
  protected $_tablename;
  protected $_id_field;
  protected $_id;

  public function __construct ( $input, DAO $dao, $tablename )
  {
    $this->setInput($input);
    $this->setDB($dao, $tablename);
  }

  public function setDB ( DAO $dao, $tablename )
  {
    $this->_dao = $dao;
    $this->_tablename = $tablename;
  }

  public function setId ( $id_field, $id_value )
  {
    $this->_id_field = $id_field;
    $this->_id = $id_value;
  }

  public function validateUniqueValue ( $prop, $label )
  {
    $value = $this->getValue($prop);

    $record = new Record($this->_dao);
    $record->setTable($this->_tablename);
    $arrCriteria = array(
        "{$prop} = ?" => $value
    );

    if ( $this->_id_field ) {
      $arrCriteria["{$this->_id_field} <> ?"] = $this->_id;
    }

    $record->setCriteria($arrCriteria);
    if ( $record->exists() )
      return JsonException::addException("Já existe um registro com este {$label}");
  }

  /*
    public function setFk ( $prop, $label, $foreign_tablename )
    {
    $value = $this->getValue($prop);

    $record = new Record($this->_dao);
    $record->setTable($foreign_tablename);
    $arrCriteria = array(
    "{$prop} = ?" => $value
    );

    $record->setCriteria($arrCriteria);
    if ( !$record->exists() )
    return JsonException::addException("{$label} não encontrado");

    $this->_table->{$prop} = $value;
    }

    public function requireRecord ( $prop, $label )
    {
    $value = $this->getValue($prop);

    $record = new Record($this->_dao);
    $record->setTable($this->_table->getName());
    $arrCriteria = array(
    "{$prop} = ?" => $value
    );

    $record->setCriteria($arrCriteria);
    if ( !$record->exists() )
    return JsonException::addException("{$label} não encontrado");
    }
   */
}

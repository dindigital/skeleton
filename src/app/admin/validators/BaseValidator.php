<?php

namespace src\app\admin\validators;

use Exception;
use Din\Filters\String\Uri;
use Din\Exception\JsonException;
use src\app\admin\helpers\MoveFiles;
use Din\DataAccessLayer\Table\iTable;
use Respect\Validation\Validator as v;
use Din\DataAccessLayer\DAO;
use src\app\admin\helpers\Record;
use Din\Crypt\Crypt;
use Din\Filters\Date\DateToSql;

class BaseValidator
{

  protected $_table;
  protected $_dao;
  protected $_input = array();
  protected $_id;

  public function __construct ( iTable $table )
  {
    $this->_table = $table;
  }

  public function setDao ( DAO $dao )
  {
    $this->_dao = $dao;
  }

  public function setId ( $id )
  {
    $this->_id = $id;
  }

  public function setInput ( $input )
  {
    $this->_input = $input;
  }

  protected function getValue ( $prop )
  {
    if ( !array_key_exists($prop, $this->_input) )
      return JsonException::addException("Índice {$prop} não existe no array de input do validator");

    return $this->_input[$prop];
  }

  public function throwException ()
  {
    JsonException::throwException();
  }

  /*
   * ============================================================================
   * SETTERS. Validam e setam
   * ===========================================================================
   */

  public function setFile ( $fieldname, MoveFiles $mf )
  {
    $file = $this->getValue($fieldname);

    /**
     * Início, verica se existe uma tentativa correta de realizar upload.
     */
    if ( !isset($file [0]) )
      return; //Array de upload vazio

    $file = $file[0];

    if ( !(count($file) == 2 && isset($file['tmp_name']) && isset($file['name'])) )
      return; //Array de upload não possui os índices necessários: tmp_name, name

    /**
     *  Chegou até aqui, então possui a intenção correta de realizar um upload
     *  Vamos validar e setar o valor do campo da tabela.
     */
    if ( !is_writable('public/system') )
      throw new Exception('A pasta public/system precisa ter permissão de escrita');

    $tmp_name = $file['tmp_name'];
    $name = $file['name'];

    $origin = 'tmp' . DIRECTORY_SEPARATOR . $tmp_name;

    if ( !is_file($origin) )
      throw new Exception('O arquivo temporário de upload não foi encontrado, certifique-se de dar permissão a pasta tmp ');

    $pathinfo = pathinfo($name);
    $name = Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];

    $table_folder = $this->_table->getName();
    $destiny = "/system/uploads/{$table_folder}/{$this->_id}/{$fieldname}/{$name}";

    $this->_table->{$fieldname} = $destiny;

    $mf->addFile($origin, 'public' . $destiny);
  }

  public function setRequiredString ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->notEmpty()->validate($value) )
      return JsonException::addException("O campo {$label} é de preenchimento obrigatório");

    $this->_table->{$prop} = $value;
  }

  public function setMinMaxString ( $prop, $label, $min = 1, $max = null )
  {
    $value = $this->getValue($prop);

    $message = "O campo {$label} precisa ter no mínimo {$min} caracteres";
    if ( !is_null($max) )
      $message .= " e no máximo {$max} caracteres";

    if ( !v::string()->length($min, $max)->validate($value) )
      return JsonException::addException($message);

    $this->_table->{$prop} = $value;
  }

  public function setLenghtString ( $prop, $label, $lenght = 1 )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->length($lenght, $lenght)->validate($value) )
      return JsonException::addException("O campo {$label} precisa ter {$lenght} caracteres");

    $this->_table->{$prop} = $value;
  }

  public function setRequiredDate ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !DateToSql::validate($value) )
      return JsonException::addException("O campo {$label} não contém uma data válida");

    $value = DateToSql::filter_date($value);

    $this->_table->{$prop} = $value;
  }

  public function setUniqueValue ( $prop, $label, $id_field = null )
  {
    $value = $this->getValue($prop);

    $record = new Record($this->_dao);
    $record->setTable($this->_table->getName());
    $arrCriteria = array(
        "{$prop} = ?" => $value
    );

    if ( $id_field ) {
      $arrCriteria["{$id_field} <> ?"] = $this->_id;
    }

    $record->setCriteria($arrCriteria);
    if ( $record->exists() )
      return JsonException::addException("Já existe um registro com este {$label}");

    $this->setRequiredString($prop, $label, $value);
  }

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

  public function setEmail ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( !v::email()->validate($value) )
      return JsonException::addException("O campo {$label} não contém um e-mail válido");

    $this->_table->{$prop} = $value;
  }

  public function setPassword ( $prop, $label, $required = true )
  {
    $value = $this->getValue($prop);

    if ( !v::string()->notEmpty()->validate($value) && $required )
      return JsonException::addException("O campo {$label} é de preenchimento obrigatório");

    if ( $value != '' ) {
      $crypt = new Crypt();
      $this->_table->{$prop} = $crypt->crypt($value);
    }
  }

  public function setIdParent ()
  {
    $id_parent = $this->getValue('id_parent');

    if ( count($id_parent) ) {
      $last = end($id_parent);
      if ( $last == '0' ) {
        if ( isset($id_parent[count($id_parent) - 2]) ) {
          $this->_table->id_parent = $id_parent[count($id_parent) - 2];
        }
      } else {
        $this->_table->id_parent = end($id_parent);
      }
    }
  }

  public function setEqualValues ( $prop1, $prop2, $label )
  {
    $value1 = $this->getValue($prop1);
    $value2 = $this->getValue($prop2);

    if ( $value1 != $value2 )
      return JsonException::addException("Os campos de {$label} devem conter o mesmo valor");

    $this->_table->{$prop1} = $value1;
  }

  /*
   * ===========================================================================
   * Apenas validam. Sem setar.
   * ===========================================================================
   */

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

  public function validateArrayNotEmpty ( $prop, $label )
  {
    $value = $this->getValue($prop);

    if ( count($prop) == 0 )
      return JsonException::addException("É necessáio pelo menos 1 {$label}");
  }

}

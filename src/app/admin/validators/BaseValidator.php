<?php

namespace src\app\admin\validators;

use Exception;
use Din\Filters\String\Uri;
use Din\Exception\JsonException;
use src\app\admin\helpers\MoveFiles;
use Din\DataAccessLayer\Table\iTable;

class BaseValidator
{

  protected $_table;

  public function __construct ( iTable $table )
  {
    $this->_table = $table;
  }

  public function setFile ( $fieldname, $file, $id, MoveFiles $mf )
  {
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
    $destiny = "/system/uploads/{$table_folder}/{$id}/{$fieldname}/{$name}";

    $this->_table->{$fieldname} = $destiny;

    $mf->addFile($origin, 'public' . $destiny);
  }

  public function throwException ()
  {
    JsonException::throwException();
  }

}

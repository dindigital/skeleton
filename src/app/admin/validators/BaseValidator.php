<?php

namespace src\app\admin\validators;

use \Exception;
use Din\Filters\String\Uri;
use Din\File\Folder;
use Din\Exception\JsonException;

class BaseValidator
{

  protected $_table;

  public function setAtivo ( $ativo )
  {
    $ativo = intval($ativo);

    $this->_table->ativo = $ativo;
  }

  public function setDel ( $del )
  {
    $del = intval($del);

    $this->_table->del = $del;
  }

  public function setIncData ()
  {
    $this->_table->inc_data = date('Y-m-d H:i:s');
  }

  public function setDelData ()
  {
    $this->_table->del_data = date('Y-m-d H:i:s');
  }

  public function setArquivo ( $fieldname, $file, $id = null )
  {
    $tmp_dir = 'tmp';
//    if ( is_null($tmp_dir) ) {
//      $tmp_dir = 'tmp';
//    }

    try {
      Folder::make_writable($tmp_dir);
    } catch (Exception $e) {
      return JsonException::addException($e->getMessage());
    }

    try {

      if ( !isset($file[0]) )
        throw new Exception($fieldname . ' é obrigatório');

      $file = $file[0]; // pegando apenas o primeiro arquivo, pois para multiplos
      // utilizamos a setGaleria..

      $folder = $this->_table->getName();

      if ( count($file) != 2 )
        throw new Exception($fieldname . ' é obrigatório');

      $tmp_name = $file['tmp_name'];
      $name = $file['name'];

      $origin = $tmp_dir . DIRECTORY_SEPARATOR . $tmp_name;

      if ( !is_file($origin) )
        throw new Exception($fieldname . ' é obrigatório ');

      if ( $id ) {
        $pathinfo = pathinfo($name);
        $name = Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];

        $destination = 'public/system/uploads/' . $folder . '/' .
                $id . '/' . $fieldname . '/' . $name;

        $diretorio = dirname($destination);
        Folder::delete($diretorio);
        Folder::make_writable($diretorio);

        rename($origin, $destination);

        $file = str_replace(PATH_REPLACE, '', $destination);

        $this->_table->$fieldname = $file;
      } else {
        return $origin;
      }
    } catch (Exception $e) {
      /* if ( $obg ) {
        return JsonException::addException($e->getMessage());
        } else */if ( is_null($id) ) {
        $this->_table->$fieldname = null;
      }
    }
  }

  public function getTable ()
  {
    return $this->_table;
  }

  public function throwException ()
  {
    JsonException::throwException();
  }

}

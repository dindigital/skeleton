<?php

namespace src\app\admin\validators;

use Exception;
use Din\Filters\String\Uri;
use Din\File\Folder;
use Din\Exception\JsonException;
use src\app\admin\helpers\Entities;
use Din\UrlShortener\Bitly\Bitly;

class BaseValidator
{

  protected $_table;

  public function setId ( $model )
  {
    $entity = Entities::getThis($model);
    $property = $entity['id'];

    $this->_table->{$property} = md5(uniqid());

    return $this->_table->{$property};
  }

  public function setActive ( $active )
  {
    $active = intval($active);

    $this->_table->active = $active;
  }

  public function setIsDel ( $is_del )
  {
    $is_del = intval($is_del);

    $this->_table->is_del = $is_del;
  }

  public function setIncDate ()
  {
    $this->_table->inc_date = date('Y-m-d H:i:s');
  }

  public function setDelDate ()
  {
    $this->_table->del_date = date('Y-m-d H:i:s');
  }

  public function setSequence ( $sequence )
  {
    if ( !is_numeric($sequence) )
      return JsonException::addException('ordem deve ser numérica');

    $this->_table->sequence = $sequence;
  }

  public function setDefaultLink ( $area, $title, $id )
  {
    $area = Uri::format($area);
    $title = Uri::format($title);
    $this->_table->link = "/{$area}/{$title}-{$id}/";
  }

  public function setShortenerLink ()
  {
    if ( URL && BITLY && $this->_table->link ) {
      $url = URL . $this->_table->link;
      $bitly = new Bitly(BITLY);
      $bitly->shorten($url);
      if ( $bitly->check() ) {
        $this->_table->short_link = $bitly;
      }
    }
  }

  public function setFile ( $fieldname, $file, $id )
  {
    $tmp_dir = 'tmp';

    try {
      Folder::make_writable($tmp_dir);
    } catch (Exception $e) {
      return JsonException::addException($e->getMessage());
    }

    try {

      if ( !isset($file [0]) )
        throw new Exception('Array de upload vazio');

      $file = $file[0];

      if ( count($file) != 2 )
        throw new Exception('Array de upload não é bidimensional');

      $tmp_name = $file['tmp_name'];
      $name = $file['name'];

      $origin = $tmp_dir . DIRECTORY_SEPARATOR .
              $tmp_name;

      if ( !is_file($origin) )
        throw new Exception('O arquivo de upload não foi encontrado: ' . $origin);

      $pathinfo = pathinfo($name);
      $name = Uri::format($pathinfo['filename']) . '.' . $pathinfo['extension'];

      $folder = $this->_table->getName();
      $destination = 'public/system/uploads/' . $folder . '/' .
              $id . '/' . $fieldname . '/' . $name;

      $diretorio = dirname($destination);
      Folder::delete($diretorio);
      Folder:: make_writable($diretorio);

      rename($origin, $destination);
      $file = str_replace(PATH_REPLACE, '', $destination);

      $this->_table->$fieldname = $file;
    } catch (Exception $e) {
      //
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

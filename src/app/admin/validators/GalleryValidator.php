<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Table\Table;
use Din\Exception\JsonException;
use Din\File\Folder;
use Din\DataAccessLayer\Select;

class GalleryValidator extends BaseValidator
{

  private $_dao;

  public function __construct ( $dao, $table )
  {
    $this->_table = new Table($table);
    $this->_dao = $dao;
  }

  public function setId ( $property )
  {
    $this->_table->{$property} = md5(uniqid());

    return $this->_table->{$property};
  }

  public function setIdTbl ( $property, $id )
  {
    $this->_table->{$property} = $id;
  }

  public function setLabel ( $label )
  {
    $this->_table->label = $label;
  }

  public function setCredit ( $credit )
  {
    $this->_table->credit = $credit;
  }

  public function setGallerySequence ( $tbl, $field, $sequence = null, $id = null )
  {
    if ( $id ) {
      $select = new Select($tbl);
      $select->addFField('sequence', 'COUNT(*)');
      $select->where(array("{$field} = ?" => $id));

      $result = $this->_dao->select($select);
      $sequence = ($result[0]['sequence'] + 1);
    }

    $this->_table->sequence = intval($sequence);
  }

  public function setGallery ( $file, $path )
  {
    if ( count($file) != 2 )
      return JsonException::addException('Erro ao ler o nome do arquivo');

    $tmp_name = $file['tmp_name'];
    $name = $file['name'];

    $tmp_dir = 'tmp';
    $origin = $tmp_dir . DIRECTORY_SEPARATOR . $tmp_name;

    if ( !is_file($origin) )
      return JsonException::addException('Arquivo não encontrado');

    $fileparts = pathinfo($name);
    $filename = $fileparts['filename'];
    $ext = '.' . strtolower($fileparts['extension']);
    $destination = 'public/system/uploads/' . $path . $filename . $ext;

    Folder::make_writable(dirname($destination));

    rename($origin, $destination);

    $file = str_replace(PATH_REPLACE, '', $destination);

    $this->_table->file = $file;
    $this->readTags($destination);
  }

  private function readTags ( $file )
  {
    if ( !in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), array('jpg', 'tiff')) )
      return;

    $exif = exif_read_data($file);

    $label = '';
    $credit = '';

    if ( isset($exif['ImageDescription']) ) {
      $label = $exif['ImageDescription'];
    }

    if ( isset($exif['Artist']) ) {
      $credit = $exif['Artist'];
    }

    $this->setLabel($label);
    $this->setCredit($credit);
  }

}

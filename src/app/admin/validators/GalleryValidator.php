<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Select;
use Din\Filters\String\Uri;
use src\app\admin\helpers\MoveFiles;
use Exception;

class GalleryValidator extends BaseValidator
{

  private $_dao;

  public function __construct ( $table, $dao )
  {
    parent::__construct($table);
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

  public function setGallery ( $file, $path, MoveFiles $mf )
  {
    /**
     * Início, verica se existe uma tentativa correta de realizar upload.
     */
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

    $destiny = "/system/uploads/{$path}/{$name}";

    $this->_table->file = $destiny;
    $this->readTags($origin);

    $mf->addFile($origin, 'public' . $destiny);
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

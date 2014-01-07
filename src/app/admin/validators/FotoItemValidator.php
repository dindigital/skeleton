<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use src\tables\FotoItemTable;
use Din\Exception\JsonException;
use Din\File\Folder;
use Din\DataAccessLayer\Select;

class FotoItemValidator extends BaseValidator
{

  private $_dao;

  public function __construct ( $dao )
  {
    $this->_table = new FotoItemTable();
    $this->_dao = $dao;
  }

  public function setIdFotoItem ()
  {
    $this->_table->id_foto_item = $this->_table->getNewId();

    return $this;
  }

  public function setIdFoto ( $id_foto )
  {
    $this->_table->id_foto = $id_foto;
  }

  public function setLegenda ( $legenda )
  {
    $this->_table->legenda = $legenda;
  }

  public function setCredito ( $credito )
  {
    $this->_table->credito = $credito;
  }

  public function setOrdem2 ( $ordem = null, $id_foto = null )
  {
    if ( $id_foto ) {
      $select = new Select('foto_item');
      $select->addFField('ordem', 'COUNT(*)');
      $select->where(array('id_foto = ?' => $id_foto));

      $result = $this->_dao->select($select);
      $ordem = ($result[0]['ordem'] + 1);
    }

    $this->_table->ordem = intval($ordem);
  }

  public function setGaleria ( $file, $path )
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
    //$filename = \lib\Validation\StringTransform::amigavel($fileparts['filename']);
    $filename = $fileparts['filename'];
    $ext = '.' . strtolower($fileparts['extension']);
    $destination = 'public/system/uploads/' . $path . $filename . $ext;

    Folder::make_writable(dirname($destination));

    rename($origin, $destination);

    $file = str_replace(PATH_REPLACE, '', $destination);

    $this->_table->arquivo = $file;
  }

}

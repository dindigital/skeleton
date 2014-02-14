<?php

namespace src\app\admin\validators;

use src\app\admin\validators\BaseValidator;
use Din\DataAccessLayer\Select;
use Din\Filters\String\Uri;
use src\app\admin\helpers\MoveFiles;
use Exception;

class GalleryValidator extends BaseValidator
{

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

  public function setGallery ( $prop, $path, MoveFiles $mf )
  {
    $file = $this->getValue($prop);

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

    $mf->addFile($origin, 'public' . $destiny);
  }

}

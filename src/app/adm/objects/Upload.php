<?php

namespace src\app\adm005\objects;

use lib\Form\Upload\Plupload\PluploadPainel;

class Upload
{

  /**
   * Devolve o campo de upload de acordo com parâmetros.
   *
   * @param string $fieldname Nome do campo
   * @param string $type Tipo de upload permitido
   * @param bool $obg Campo obrigatório?
   * @param bool $multiple Multiplos arquivos?
   * @return string
   */
  public static function get ( $fieldname, $type, $obg = false, $multiple = false, $uploader = null )
  {
    $upl = PluploadPainel::getButton($fieldname, $type, $obg, $multiple, $uploader);
    //$upl = \lib\Form\Upload\Uploadify\UploadifyPainel::getButton($fieldname, $type, $obg, $multiple);
    return $upl;
  }

  /**
   * Seta um campo upload em $this->table->$fieldname
   *
   * @param string $fieldname
   * @param string $type
   * @param bool $obg
   * @param bool $multiple Multiplos arquivos?
   * @param bool $preview Mostrar preview?
   */
  public static function set ( $std, $fieldname, $type, $obg = false, $multiple = false, $preview = true )
  {
    $value = $std->$fieldname;

    $std->$fieldname = self::get($fieldname, $type, $obg, $multiple);
    if ( !is_null($value) && $preview ) {
      $std->$fieldname .= Preview::preview($value);
    }
  }

}


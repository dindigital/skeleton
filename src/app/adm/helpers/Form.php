<?php

namespace src\app\adm\helpers;

use src\app\adm\helpers\PluploadPainel;
use Din\Form\Upload;
use Din\Form\FileBrowser\CKFinder\CKFinder;
use Din\Form\Dropdown\Dropdown;
use Din\Form\Textarea\Ckeditor\Ckeditor;

class Form
{

  /**
   * Retorna uma string contendo o campo ck
   *
   * @param string $name
   * @param string $value
   * @return string
   */
  public static function Ck ( $name, $value = '' )
  {
    $ck = new Ckeditor($name);

    return $ck->getElement($value);
  }

  /**
   * Retorna o ckfinder de acordo com parâmetros
   *
   * @param string $name
   * @param string $startUpPath
   * @return string
   */
  public static function Ckfinder ( $name, $startUpPath = null, $class = null )
  {
    $o = new CKFinder($name);

    if ( $startUpPath )
      $o->setStartUpPath('Videos:/');

    if ( $class )
      $o->setClassTextfield($class);

    return $o->getElement();
  }

  /**
   * Monta um dropdown de acordo com parâmetros e retorna em string
   *
   * @param type $name
   * @param array $array
   * @param string $selected
   * @param string $firstOption
   * @param string $id
   * @param string $class
   * @return string
   */
  public static function Dropdown ( $name, $array, $selected = '', $firstOption = null, $id = null, $class = null )
  {
    $d = new Dropdown($name);
    if ( $class )
      $d->setClass('uniform ' . $class);
    else
      $d->setClass('uniform');

    $d->setSelected($selected);
    $d->setOptionsArray($array);

    if ( $firstOption )
      $d->setFirstOpt($firstOption);

    if ( $id )
      $d->setId($id);

    return $d->getElement();
  }

  /**
   * Devolve o campo de upload de acordo com parâmetros.
   *
   * @param string $fieldname Nome do campo
   * @param string $type Tipo de upload permitido
   * @param bool $obg Campo obrigatório?
   * @param bool $multiple Multiplos arquivos?
   * @return string
   */
  public static function Upload ( $fieldname, $value, $type, $obg = false, $multiple = false, $preview = true )
  {
    $upl = PluploadPainel::getButton($fieldname, $type, $obg, $multiple, null);
    if ( !is_null($value) && $preview ) {
      $upl .= Preview::preview($value);
    }

    return $upl;
  }

}

<?php

namespace Admin\Helpers;

use Admin\Helpers\PluploadPainel;
use Din\Form\FileBrowser\CKFinder\CKFinder;
use Din\Form\Dropdown\Dropdown;
use Din\Form\Textarea\Ckeditor\Ckeditor;
use Din\Form\Listbox\Listbox;

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
      $d->setClass('form-control ' . $class);
    else
      $d->setClass('select2');

    $d->setSelected($selected);
    $d->setOptionsArray($array);

    if ( $firstOption )
      $d->setFirstOpt($firstOption);

    if ( $id )
      $d->setId($id);

    return $d->getElement();

  }

  public static function Listbox ( $name, $array, $selected = array(), $class = '' )
  {
    $d = new Listbox($name);
    $d->setOptionsArray($array);
    $d->setId($name);
    $d->setClass('form-control listbox ' . $class);
    $d->setSelected($selected);

    return $d->getElement();

  }

  public static function Upload ( $fieldname, $value, $type, $multiple = false, $preview = true )
  {
    $upl = PluploadPainel::getButton($fieldname, $type, false, $multiple, null);
    if ( !is_null($value) && $preview ) {
      $upl .= Preview::preview($value);
    }

    return $upl;

  }

}

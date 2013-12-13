<?php

namespace src\app\adm005\objects;

class Ckfinder
{

  /**
   * Retorna o ckfinder de acordo com parâmetros
   *
   * @param string $name
   * @param string $startUpPath
   * @return string
   */
  public static function get ( $name, $startUpPath = null, $class = null )
  {
    $o = new \lib\Form\Browser\CKFinder\CKFinder($name);

    if ( $startUpPath )
      $o->setStartUpPath('Videos:/');

    if ( $class )
      $o->setClassTextfield($class);

    return $o->getElement();
  }

  /**
   * Seta o ckfinder em $this->table->{$prop_name}
   *
   * @param stdClass $std
   * @param string $prop_name
   * @param string $startUpPath
   */
  public static function set ( $std, $prop_name, $startUpPath = null, $class = null )
  {
    $std->{$prop_name} = $this->get_ckfinder($prop_name, $startUpPath, $class);
  }

}


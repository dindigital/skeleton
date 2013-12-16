<?php

namespace src\app\adm005\objects;

class Dropdown
{

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
  public static function get ( $name, $array, $selected = '', $firstOption = null, $id = null, $class = null )
  {
    $d = new \lib\Form\Dropdown\Dropdown($name);
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
   * Monta um dropdown de acordo com parâmetros e salva dentro de $this->table->$name
   *
   * @param stdClass $std
   * @param type $name
   * @param type $array
   * @param type $firstOption
   */
  public static function set ( $std, $name, $array, $firstOption = '', $id = null )
  {
    $drop = self::get($name, $array, $std->$name, $firstOption, $id);
    $std->$name = $drop;
  }

}


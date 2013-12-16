<?php

namespace src\app\adm005\objects;

class Ck
{

  /**
   * Retorna uma string contendo o campo ck
   *
   * @param string $name
   * @param string $value
   * @return string
   */
  public static function get ( $name, $value = '' )
  {
    $ck = new \lib\Form\Textarea\Ckeditor\Ckeditor($name);

    return $ck->getElement($value);
  }

  /**
   * Seta um campo ck em $this->table->$name com o valor de
   * $this->table->$name
   *
   * @param string $name
   */
  public static function set ( $std, $name )
  {
    $std->$name = self::get($name, $std->$name);
  }

}


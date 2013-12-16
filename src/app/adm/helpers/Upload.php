<?php

namespace src\app\adm\helpers;

use src\app\adm\helpers\PluploadPainel;

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
  public static function get ( $fieldname, $value, $type, $obg = false, $multiple = false, $preview = true )
  {
    $upl = PluploadPainel::getButton($fieldname, $type, $obg, $multiple, null);
    if ( !is_null($value) && $preview ) {
      $upl .= Preview::preview($value);
    }

    return $upl;
  }

}

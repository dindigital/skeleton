<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Galeria;

class FotoViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);
      $row['data'] = DateFormat::filter_date($row['data']);
      $row['galeria'] = Galeria::get(@$row['galeria'], 'galeria');
    }

    $row['galeria_uploader'] = Form::Upload('galeria_uploader', '', 'imagem', true, false);

    return $row;
  }

}

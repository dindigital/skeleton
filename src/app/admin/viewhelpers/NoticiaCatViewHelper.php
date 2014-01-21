<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;

class NoticiaCatViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_data'] = DateFormat::filter_date($row['inc_data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['titulo'] = htmlspecialchars($arrFilters['titulo']);
    $arrFilters['home'] = Form::Dropdown('home', Arrays::$simNao, $arrFilters['home'], 'Home?');

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);

      $capa = $row['capa'];
    } else {
      $capa = '';
    }

    $row['capa'] = Form::Upload('capa', $capa, 'imagem');

    return $row;
  }

}

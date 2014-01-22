<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class PaginaCatViewHelper
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
    $arrFilters['titulo'] = Html::scape($arrFilters['titulo']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['titulo'] = Html::scape(@$row['titulo']);
    $row['conteudo'] = Form::Ck('conteudo', @$row['conteudo']);
    $row['capa'] = Form::Upload('capa', @$row['capa'], 'imagem');

    return $row;
  }

}

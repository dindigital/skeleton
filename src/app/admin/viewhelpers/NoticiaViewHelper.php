<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;

class NoticiaViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['titulo'] = htmlspecialchars($arrFilters['titulo']);

    return $arrFilters;
  }

  public static function formatRow ( $row, $noticia_cat_dropdown )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);
      $row['data'] = DateFormat::filter_date($row['data']);
      $row['chamada'] = htmlspecialchars($row['chamada']);

      $corpo = $row['corpo'];
      $capa = $row['capa'];
      $id_noticia_cat = $row['id_noticia_cat'];
    } else {
      $corpo = '';
      $capa = '';
      $id_noticia_cat = '';
    }

    $row['corpo'] = Form::Ck('corpo', $corpo);
    $row['capa'] = Form::Upload('capa', $capa, 'imagem');
    $row['id_noticia_cat'] = Form::Dropdown('id_noticia_cat', $noticia_cat_dropdown, $id_noticia_cat, 'Selecione um Menu');

    return $row;
  }

}

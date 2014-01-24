<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class NoticiaViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $noticia_cat_dropdown )
  {
    $arrFilters['titulo'] = Html::scape($arrFilters['titulo']);
    $arrFilters['id_noticia_cat'] = Form::Dropdown('id_noticia_cat', $noticia_cat_dropdown, $arrFilters['id_noticia_cat'], 'Filtro por Categoria');

    return $arrFilters;
  }

  public static function formatRow ( $row, $noticia_cat_dropdown, $listbox )
  {
    $row['titulo'] = Html::scape(@$row['titulo']);
    $row['data'] = DateFormat::filter_date(@$row['data']);
    $row['chamada'] = Html::scape(@$row['chamada']);
    $row['corpo'] = Form::Ck('corpo', @$row['corpo']);
    $row['capa'] = Form::Upload('capa', @$row['capa'], 'imagem');
    $row['id_noticia_cat'] = Form::Dropdown('id_noticia_cat', $noticia_cat_dropdown, @$row['id_noticia_cat'], 'Selecione um Menu');
    $row['r_noticia_foto'] = Form::Listbox('r_noticia_foto', $listbox['foto_values'], $listbox['foto_selected']);
    $row['r_noticia_video'] = Form::Listbox('r_noticia_video', $listbox['video_values'], $listbox['video_selected'], 'ajaxli');

    return $row;
  }

}

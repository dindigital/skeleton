<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class NewsViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $news_cat_dropdown )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);
    $arrFilters['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $arrFilters['id_news_cat'], 'Filtro por Categoria');

    return $arrFilters;
  }

  public static function formatRow ( $row, $news_cat_dropdown, $listbox )
  {
    $row['title'] = Html::scape(@$row['title']);
    $row['date'] = isset($row['date']) ? DateFormat::filter_date($row['date']) : date('d/m/Y');
    $row['head'] = Html::scape(@$row['head']);
    $row['body'] = Form::Ck('body', @$row['body']);
    $row['cover'] = Form::Upload('cover', @$row['cover'], 'imagem');
    $row['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, @$row['id_news_cat'], 'Selecione uma Categoria');
    $row['r_news_photo'] = Form::Listbox('r_news_photo', $listbox['photo_values'], array_keys($listbox['photo_selected']));
    $row['r_news_video'] = Form::Listbox('r_news_video', $listbox['video'], array_keys($listbox['video']), 'ajaxli');

    return $row;
  }

}

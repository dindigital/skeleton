<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

class NewsViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
      $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_news'], 'form-control drop_sequence');
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $news_cat_dropdown )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);
    $arrFilters['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $arrFilters['id_news_cat'], 'Filtro por Categoria');

    return $arrFilters;
  }

  public static function formatRow ( $row, $news_cat_dropdown )
  {
    $row['title'] = Html::scape($row['title']);
    $row['date'] = isset($row['date']) ? DateFormat::filter_date($row['date']) : date('d/m/Y');
    $row['head'] = Html::scape($row['head']);
    $row['body'] = Form::Ck('body', $row['body']);
    $row['uri'] = Link::formatUri($row['uri']);
    $row['cover'] = Form::Upload('cover', $row['cover'], 'image');
    $row['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $row['id_news_cat'], 'Selecione uma Categoria');

    return $row;
  }

}

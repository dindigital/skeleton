<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class PageCatViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page_cat'], 'form-control drop_sequence');
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['title'] = Html::scape(@$row['title']);
    $row['content'] = Form::Ck('content', @$row['content']);
    $row['cover'] = Form::Upload('cover', @$row['cover'], 'image');

    return $row;
  }

}

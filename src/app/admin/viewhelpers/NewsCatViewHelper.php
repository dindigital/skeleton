<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use Din\Filters\String\Html;

class NewsCatViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if ( isset($row['sequence_list_array']) ) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_news_cat'], 'drop_sequence');
      }
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);
    $arrFilters['is_home'] = Form::Dropdown('is_home', Arrays::$simNao, $arrFilters['is_home'], 'Home?');

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['title'] = Html::scape(@$row['title']);
    $row['cover'] = Form::Upload('cover', @$row['cover'], 'image');

    return $row;
  }

}

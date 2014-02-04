<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

class VideoViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
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
    $row['title'] = Html::scape($row['title']);
    $row['date'] = DateFormat::filter_date($row['date']);
    $row['uri'] = Link::formatUri($row['uri']);

    return $row;
  }

}

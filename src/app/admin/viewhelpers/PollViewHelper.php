<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

class PollViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['question'] = Html::scape($arrFilters['question']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['question'] = Html::scape($row['question']);
    $row['uri'] = Link::formatUri($row['uri']);

    return $row;
  }

}

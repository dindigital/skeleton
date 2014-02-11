<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

class MailingGroupViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['name'] = Html::scape($row['name']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['name'] = Html::scape($arrFilters['name']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['name'] = Html::scape($row['name']);

    return $row;
  }

}

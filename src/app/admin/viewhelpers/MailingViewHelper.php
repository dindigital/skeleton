<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\String\Html;

class MailingViewHelper
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
    $arrFilters['email'] = Html::scape($arrFilters['email']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['name'] = Html::scape($row['name']);
    $row['email'] = Html::scape($row['email']);

    return $row;
  }

}

<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;

class VideoViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['titulo'] = htmlspecialchars($row['titulo']);
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['titulo'] = htmlspecialchars($arrFilters['titulo']);

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);
      $row['data'] = DateFormat::filter_date($row['data']);
    }

    return $row;
  }

}

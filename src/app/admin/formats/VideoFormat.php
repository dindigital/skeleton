<?php

namespace src\app\admin\formats;

use Din\Filters\Date\DateFormat;

class VideoFormat
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['titulo'] = htmlspecialchars($row['titulo']);
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
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

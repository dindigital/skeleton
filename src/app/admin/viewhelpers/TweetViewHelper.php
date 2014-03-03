<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;

class TweetViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_dateTimeExtensive($row['date']);
    }

    return $result;
  }

}

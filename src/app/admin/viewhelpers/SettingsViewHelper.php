<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\String\Html;

class SettingsViewHelper
{

  public static function formatRow ( $row )
  {
    $row['home_title'] = Html::scape($row['home_title']);
    $row['home_description'] = Html::scape($row['home_description']);
    $row['home_keywords'] = Html::scape($row['home_keywords']);
    $row['title'] = Html::scape($row['title']);
    $row['description'] = Html::scape($row['description']);
    $row['keywords'] = Html::scape($row['keywords']);

    return $row;
  }

}

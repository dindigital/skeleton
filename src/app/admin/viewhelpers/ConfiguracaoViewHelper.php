<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\String\Html;

class ConfiguracaoViewHelper
{

  public static function formatRow ( $row )
  {
    $row['title_home'] = Html::scape($row['title_home']);
    $row['description_home'] = Html::scape($row['description_home']);
    $row['keywords_home'] = Html::scape($row['keywords_home']);
    $row['title_interna'] = Html::scape($row['title_interna']);
    $row['description_interna'] = Html::scape($row['description_interna']);
    $row['keywords_interna'] = Html::scape($row['keywords_interna']);

    return $row;
  }

}

<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\String\Html;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Link;

class PublicationViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
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
    $row['file'] = Form::Upload('file', $row['file'], 'document');
    $row['uri'] = Link::formatUri($row['uri']);

    return $row;
  }

}

<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Gallery;
use Din\Filters\String\Html;

class PhotoViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
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
    $row['title'] = Html::scape(@$row['title']);
    if ( isset($row['date']) ) {
      $row['date'] = DateFormat::filter_date($row['date']);
    }
    $row['gallery'] = Gallery::get(@$row['gallery'], 'gallery');
    $row['gallery_uploader'] = Form::Upload('gallery_uploader', '', 'imagem', true, false);

    return $row;
  }

}

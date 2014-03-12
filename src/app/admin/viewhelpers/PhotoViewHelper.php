<?php

namespace src\app\admin\viewhelpers;

class PhotoViewHelper
{

  public static function formatRow ( $row )
  {
    $row['title'] = Html::scape($row['title']);
    $row['date'] = DateFormat::filter_date($row['date']);
    $uploader = Form::Upload('gallery_uploader', '', 'image', true, false);
    $row['gallery'] = $uploader . Gallery::get($row['gallery'], 'gallery');
    $row['uri'] = Link::formatUri($row['uri']);

    return $row;
  }

}

<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class AdminViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['name'] = Html::scape($arrFilters['name']);
    $arrFilters['email'] = Html::scape($arrFilters['email']);

    return $arrFilters;
  }

  public static function formatRow ( $row, $permissao_listbox )
  {
    $row['name'] = Html::scape(@$row['name']);
    $row['avatar'] = Form::Upload('avatar', @$row['avatar'], 'image');
    $row['permission'] = Form::Listbox('permission', $permissao_listbox, json_decode(@$row['permission']));

    return $row;
  }

}

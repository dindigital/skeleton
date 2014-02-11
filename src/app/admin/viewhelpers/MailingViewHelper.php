<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\String\Html;
use src\app\admin\helpers\Form;

class MailingViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['name'] = Html::scape($row['name']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $mg_list )
  {
    $arrFilters['name'] = Html::scape($arrFilters['name']);
    $arrFilters['email'] = Html::scape($arrFilters['email']);
    $arrFilters['mailing_group'] = Form::Dropdown('mailing_group', $mg_list, $arrFilters['mailing_group'], 'Filtro por Grupo');

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    $row['name'] = Html::scape($row['name']);
    $row['email'] = Html::scape($row['email']);

    return $row;
  }

}

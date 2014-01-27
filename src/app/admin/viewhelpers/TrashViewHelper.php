<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class TrashViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['del_date'] = DateFormat::filter_date($row['del_date'], 'd/m/Y H:i');
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $dropdown_secao )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);
    $arrFilters['section'] = Form::Dropdown('section', $dropdown_secao, $arrFilters['section'], 'Filtro por Seção');

    return $arrFilters;
  }

}

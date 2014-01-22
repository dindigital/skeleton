<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class LixeiraViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['del_data'] = DateFormat::filter_date($row['del_data'], 'd/m/Y H:i');
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $dropdown_secao )
  {
    $arrFilters['titulo'] = Html::scape($arrFilters['titulo']);
    $arrFilters['secao'] = Form::Dropdown('secao', $dropdown_secao, $arrFilters['secao'], 'Filtro por Seção');

    return $arrFilters;
  }

}

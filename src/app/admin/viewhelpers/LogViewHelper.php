<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use src\app\admin\helpers\Entities;
use Din\Filters\String\Html;

class LogViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['action'] = Arrays::$logAcao[$row['action']];
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      $atual = Entities::getEntityByName($row['name']);
      $result[$i]['name'] = $atual['section'];
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $dropdown_name )
  {
    $arrFilters['admin'] = Html::scape($arrFilters['admin']);
    $arrFilters['description'] = Html::scape($arrFilters['description']);
    $arrFilters['action'] = Form::Dropdown('action', Arrays::$logAcao, $arrFilters['action'], 'Filtro por Ação');
    $arrFilters['name'] = Form::Dropdown('name', $dropdown_name, $arrFilters['name'], 'Filtro por Seção');

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['description'] = Html::scape($row['description']);
      $row['inc_date'] = DateFormat::filter_date($row['inc_date']);
      $row['action'] = Arrays::$logAcao[$row['action']];
      $row['cont'] = json_decode($row['content']);
    }

    return $row;
  }

}

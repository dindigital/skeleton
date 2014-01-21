<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use src\app\admin\helpers\Entities;

class LogViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['acao'] = Arrays::$logAcao[$row['acao']];
      $result[$i]['inc_data'] = DateFormat::filter_date($row['inc_data']);
      $atual = Entities::getEntityByName($row['name']);
      $result[$i]['name'] = $atual['secao'];
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $dropdown_name )
  {
    $arrFilters['usuario'] = htmlspecialchars($arrFilters['usuario']);
    $arrFilters['descricao'] = htmlspecialchars($arrFilters['descricao']);
    $arrFilters['acao'] = Form::Dropdown('acao', Arrays::$logAcao, $arrFilters['acao'], 'Filtro por Ação');
    $arrFilters['name'] = Form::Dropdown('name', $dropdown_name, $arrFilters['name'], 'Filtro por Seção');

    return $arrFilters;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['descricao'] = htmlspecialchars($row['descricao']);
      $row['inc_data'] = DateFormat::filter_date($row['inc_data']);
      $row['acao'] = Arrays::$logAcao[$row['acao']];
      $row['conteudo'] = json_decode($row['conteudo']);
    }

    return $row;
  }

}

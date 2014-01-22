<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class UsuarioViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_data'] = DateFormat::filter_date($row['inc_data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters )
  {
    $arrFilters['nome'] = Html::scape($arrFilters['nome']);
    $arrFilters['email'] = Html::scape($arrFilters['email']);

    return $arrFilters;
  }

  public static function formatRow ( $row, $permissao_listbox )
  {
    $row['nome'] = Html::scape(@$row['nome']);
    $row['avatar'] = Form::Upload('avatar', @$row['avatar'], 'imagem');
    $row['avatar2'] = Form::Upload('avatar2', @$row['avatar2'], 'imagem');
    $row['avatar3'] = Form::Upload('avatar3', @$row['avatar3'], 'imagem');
    $row['permissao'] = Form::Listbox('permissao', $permissao_listbox, json_decode(@$row['permissao']));

    return $row;
  }

}

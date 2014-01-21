<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;

class PaginaCatViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['data'] = DateFormat::filter_date($row['data']);
    }

    return $result;
  }

  public static function formatRow ( $row )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);

      $conteudo = $row['conteudo'];
      $capa = $row['capa'];
    } else {
      $conteudo = '';
      $capa = '';
    }

    $row['conteudo'] = Form::Ck('conteudo', $conteudo);
    $row['capa'] = Form::Upload('capa', $capa, 'imagem');

    return $row;
  }

}

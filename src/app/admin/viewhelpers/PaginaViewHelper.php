<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;

class PaginaViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_data'] = DateFormat::filter_date($row['inc_data']);
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $pagina_cat_dropdown )
  {
    $arrFilters['titulo'] = htmlspecialchars($arrFilters['titulo']);
    $arrFilters['id_pagina_cat'] = Form::Dropdown('id_pagina_cat', $pagina_cat_dropdown, @$arrFilters['id_pagina_cat'], 'Filtro por Menu');

    return $arrFilters;
  }

  public static function formatRow ( $row, $pagina_cat_dropdown )
  {
    if ( !empty($row) ) {
      $row['titulo'] = htmlspecialchars($row['titulo']);

      $conteudo = $row['conteudo'];
      $capa = $row['capa'];
      $id_pagina_cat = $row['id_pagina_cat'];
      $infinito = $row['infinito'];
    } else {
      $conteudo = '';
      $capa = '';
      $id_pagina_cat = '';
      $infinito = array();
    }

    $row['infinito'] = array();
    foreach ( $infinito as $i => $drop ) {
      $addClass = 'other';
      $row['infinito'][] = self::format_infinity_dropdown($drop['dropdown'], $drop['selected'], $addClass);
    }

    $row['id_pagina_cat'] = Form::Dropdown('id_pagina_cat', $pagina_cat_dropdown, $id_pagina_cat, 'Selecione um Menu', null, 'ajax_intinify_cat');
    $row['conteudo'] = Form::Ck('conteudo', $conteudo);
    $row['capa'] = Form::Upload('capa', $capa, 'imagem');

    return $row;
  }

  public static function format_infinity_dropdown ( $dropdown, $selected = null, $addClass = null )
  {
    $addClass = $addClass ? ' ' . $addClass : '';
    $dropdown = Form::Dropdown('id_parent[]', $dropdown, $selected, 'Subnível de Página', null, 'ajax_infinity');

    return $dropdown;
  }

}

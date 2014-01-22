<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

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
    $arrFilters['titulo'] = Html::scape($arrFilters['titulo']);
    $arrFilters['id_pagina_cat'] = Form::Dropdown('id_pagina_cat', $pagina_cat_dropdown, @$arrFilters['id_pagina_cat'], 'Filtro por Menu');

    return $arrFilters;
  }

  public static function formatRow ( $row, $pagina_cat_dropdown )
  {
    $row['titulo'] = Html::scape(@$row['titulo']);
    $row['conteudo'] = Form::Ck('conteudo', @$row['conteudo']);
    $row['capa'] = Form::Upload('capa', @$row['capa'], 'imagem');
    $row['id_pagina_cat'] = Form::Dropdown('id_pagina_cat', $pagina_cat_dropdown, @$row['id_pagina_cat'], 'Selecione um Menu', null, 'ajax_intinify_cat');

    foreach ( (array) @$row['infinito'] as $i => $drop ) {
      $addClass = 'other';
      $row['infinito'][] = self::formatInfinityDropdown($drop['dropdown'], $drop['selected'], $addClass);
    }

    return $row;
  }

  public static function formatInfinityDropdown ( $dropdown, $selected = null )
  {
    $dropdown = Form::Dropdown('id_parent[]', $dropdown, $selected, 'Subnível de Página', null, 'ajax_infinity');

    return $dropdown;
  }

}

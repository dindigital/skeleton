<?php

namespace src\app\admin\viewhelpers;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;

class PageViewHelper
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if ( isset($row['sequence_list_array']) ) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page'], 'drop_sequence');
      }
    }

    return $result;
  }

  public static function formatFilters ( $arrFilters, $page_cat_dropdown )
  {
    $arrFilters['title'] = Html::scape($arrFilters['title']);
    $arrFilters['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $arrFilters['id_page_cat'], 'Filtro por Menu');

    return $arrFilters;
  }

  public static function formatRow ( $row, $page_cat_dropdown )
  {
    $row['title'] = Html::scape($row['title']);
    $row['content'] = Form::Ck('content', $row['content']);
    $row['cover'] = Form::Upload('cover', $row['cover'], 'image');
    $row['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $row['id_page_cat'], 'Selecione um Menu', null, 'ajax_intinify_cat');

    $infinite_drop = array();
    foreach ( (array) $row['id_parent'] as $i => $drop ) {
      $addClass = 'other';
      $infinite_drop[] = self::formatInfiniteDropdown($drop['dropdown'], $drop['selected'], $addClass);
    }

    $row['id_parent'] = $infinite_drop;

    return $row;
  }

  public static function formatInfiniteDropdown ( $dropdown, $selected = null )
  {
    $dropdown = Form::Dropdown('id_parent[]', $dropdown, $selected, 'Subnível de Página', null, 'ajax_infinity');

    return $dropdown;
  }

}

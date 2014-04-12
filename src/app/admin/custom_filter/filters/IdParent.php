<?php

namespace src\app\admin\custom_filter\filters;

use Din\TableFilter\AbstractFilter;

class IdParent extends AbstractFilter
{

  public function filter ( $field )
  {
    $id_parent = $this->getValue($field);

    if ( count($id_parent) ) {
      $last = end($id_parent);
      if ( $last == '0' ) {
        if ( isset($id_parent[count($id_parent) - 2]) ) {
          $this->_table->{$field} = $id_parent[count($id_parent) - 2];
        }
      } else {
        $this->_table->{$field} = end($id_parent);
      }
    }

  }

}

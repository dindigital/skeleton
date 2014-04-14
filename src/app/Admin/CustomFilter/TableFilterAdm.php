<?php

namespace Admin\CustomFilter;

use Din\TableFilter\TableFilter;
use Exception;

/**
 * @method \Din\TableFilter\FilterInterface defaultUri($title,$id = '', $prefix = '' )
 * @method \Din\TableFilter\FilterInterface idParent()
 * @method \Din\TableFilter\FilterInterface labelCredit()
 * @method \Din\TableFilter\FilterInterface sequence(DAO $dao, Entity $entity)
 * @method \Din\TableFilter\FilterInterface shortenerLink()
 * @method \Din\TableFilter\FilterInterface uploaded($path, $has_upload, MoveFiles $mf)
 */
class TableFilterAdm extends TableFilter
{

  public function instanciateFilter ( $namespace, $classname, $arguments )
  {
    try {

      return parent::instanciateFilter($namespace, $classname, $arguments);
    } catch (Exception $ex) {
      $namespace = __NAMESPACE__ . '\Filters\\';

      return parent::instanciateFilter($namespace, $classname, $arguments);
    }

  }

}

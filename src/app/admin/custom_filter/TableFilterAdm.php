<?php

namespace src\app\admin\custom_filter;

use Din\TableFilter\TableFilter;

/**
 * @method setDefaultUri($field,$id = '', $prefix = '' )
 * @method setIdParent($field)
 * @method setLabelCredit($field)
 * @method setSequence($field,DAO $dao, Entity $entity)
 * @method setShortenerLink($field)
 * @method setUploaded($field,$path, $has_upload, MoveFiles $mf)
 */
class TableFilterAdm extends TableFilter
{

  protected function getCustomFilter ( $classname )
  {
    $this->_fqn = __NAMESPACE__ . '\filters\\' . $classname;

    return true;
  }

}

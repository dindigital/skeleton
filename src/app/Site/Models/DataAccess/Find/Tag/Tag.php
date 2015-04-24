<?php

namespace Site\Models\DataAccess\Find\Tag;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\TagCollection;

class Tag extends AbstractFind
{

  /**
   *
   * @param array $arr_id
   * @return TagCollection
   */
  public function getByArrId ( $arr_id )
  {
    $select = new Select2('tag');
    $select->addField('id_tag');
    $select->addField('title');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'id_tag IN (?)' => $arr_id,
    )));

    $select->order_by($this->_order);

    $result = $this->_dao->select_iterator($select, new Entity\Tag, new TagCollection);

    return $result;

  }

}

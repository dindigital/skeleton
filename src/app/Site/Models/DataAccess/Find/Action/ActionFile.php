<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionFileCollection;

class ActionFile extends AbstractFind
{

  private function getSelectByIdAction ( $id_action )
  {
    $select = new Select2('action_file');
    $select->addField('id_action_file');
    $select->addField('title');
    $select->addField('information');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'id_action = ?' => $id_action,
    )));

    return $select;

  }

  /**
   *
   * @param string $id_action
   * @return int
   */
  public function getCountByIdAction ( $id_action )
  {
    $select = $this->getSelectByIdAction($id_action);

    $count = $this->_dao->select_count($select);

    return $count;

  }

  public function getByIdAction ( $id_action )
  {
    $select = $this->getSelectByIdAction($id_action);
    $select->order_by('sequence');
    if ( $this->_limit ) {
      $select->limit($this->_limit);
    }

    $collection = $this->_dao->select_iterator($select, new Entity\ActionFile, new ActionFileCollection);

    return $collection;

  }

  /**
   *
   * @param string $id_action_file
   * @return ActionFileCollection
   */
  public function getByIdActionFile ( $id_action_file )
  {
    $select = new Select2('action_file');
    $select->addField('id_action_file');
    $select->addField('title');
    $select->addField('information');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'id_action_file = ?' => $id_action_file,
    )));

    $select->limit(1);

    $collection = $this->_dao->select_iterator($select, new Entity\ActionFile, new ActionFileCollection);

    return $collection;

  }

}

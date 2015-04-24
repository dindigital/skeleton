<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionCatCollection;

class ActionCat extends AbstractDAOClient
{

  public function getWidget ( $limit )
  {
    $select = new Select2('action_cat');
    $select->addField('id_action_cat');
    $select->addField('title');
    $select->addField('uri');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'is_home = ?' => 1,
    )));

    $select->order_by('sequence');
    $select->limit($limit);

    $collection = $this->_dao->select_iterator($select, new Entity\ActionCat, new ActionCatCollection);

    return $collection;

  }

  public function getList ()
  {
    $select = new Select2('action_cat');
    $select->addField('id_action_cat');
    $select->addField('title');
    $select->addField('uri');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $select->order_by('title');

    $collection = $this->_dao->select_iterator($select, new Entity\ActionCat, new ActionCatCollection);

    return $collection;

  }

}

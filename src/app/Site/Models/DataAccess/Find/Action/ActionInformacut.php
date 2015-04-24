<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionCollection;

class ActionInformacut extends AbstractDAOClient
{

  public function getCollection ()
  {

    $select = new Select2('action');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('cover');
    $select->addField('description');
    $select->order_by('action.sequence=0, action.sequence, action.date DESC');
    $select->limit(1);

    $select->inner_join('action_cat', 'id_action_cat', 'id_action_cat');
    $select->addField('title', 'category', 'action_cat');

    $select->where(new Criteria(array(
        'action.is_active = ?' => 1,
        'action.is_del = ?' => 0,
        'action_cat.is_newsletter = ?' => 1,
    )));

    $result = $this->_dao->select_iterator($select, new Entity\Action, new ActionCollection);

    return $result;

  }

}

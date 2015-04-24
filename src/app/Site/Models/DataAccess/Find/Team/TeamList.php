<?php

namespace Site\Models\DataAccess\Find\Team;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class TeamList extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('team');
    $this->_select->addField('id_team');
    $this->_select->addField('name');
    $this->_select->addField('cover');
    $this->_select->addField('area');
    $this->_select->addField('description');
    $this->_select->addField('instagram');
    $this->_select->addField('facebook');

    $this->_criteria = array(
        'team.is_del = ?' => 0,
        'team.is_active = ?' => 1,
    );

  }

  public function setLimit($limit)
  {
     $this->_select->limit($limit);
  }

  public function setOrder($order)
  {
     $this->_select->order_by($order);
  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\Team,
      new \Site\Models\DataAccess\Collection\TeamCollection);

  }

}

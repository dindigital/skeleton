<?php

namespace Site\Models\DataAccess\Find\RSS\Areas;

use Site\Models\DataAccess\Find\RSS\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Action implements AreaInterface
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    $this->_select = new Select2('action');
    $this->_select->addSField('Ações', 'area');
    $this->_select->addField('id_action', 'id');
    $this->_select->addField('title');
    $this->_select->addField('description', 'head');
    $this->_select->addField('date');
    $this->_select->addField('cover');
    $this->_select->addField('uri');

    $this->_criteria['is_active = ?'] = 1;
    $this->_criteria['is_del = ?'] = 0;

  }

  public function getSelect ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    return $this->_select;

  }

}

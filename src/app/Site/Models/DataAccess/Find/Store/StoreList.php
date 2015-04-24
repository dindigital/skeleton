<?php

namespace Site\Models\DataAccess\Find\Store;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class StoreList extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('store');
    $this->_select->addField('title');
    $this->_select->addField('cover');
    $this->_select->addField('logo');
    $this->_select->addField('address');
    $this->_select->addField('address_google');
    $this->_select->addField('phone');
    $this->_select->addField('email');

    $this->_select->order_by('store.sequence');

    $this->_criteria = array(
        'store.is_del = ?' => 0,
        'store.is_active = ?' => 1,
    );

  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\Store,
      new \Site\Models\DataAccess\Collection\StoreCollection);

  }

}

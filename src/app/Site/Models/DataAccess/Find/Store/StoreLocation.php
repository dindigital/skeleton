<?php

namespace Site\Models\DataAccess\Find\Store;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class StoreLocation extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('store');
    $this->_select->addField('title');
    $this->_select->addField('address_latitude', 'lat');
    $this->_select->addField('address_longitude', 'lng');

    $this->_select->order_by('store.title');

    $this->_criteria = array(
        'store.is_active = ?' => 1,
    );

  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getData ()
  {
    return $this->_dao->select($this->_select);

  }

}

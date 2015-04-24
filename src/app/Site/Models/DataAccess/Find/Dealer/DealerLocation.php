<?php

namespace Site\Models\DataAccess\Find\Dealer;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class DealerLocation extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('dealer');
    $this->_select->addField('id_dealer', 'id');
    $this->_select->addField('name');
    $this->_select->addField('address_latitude', 'lat');
    $this->_select->addField('address_longitude', 'lng');
    $this->_select->addField('address');
    $this->_select->addField('address_area','address2');
    $this->_select->addField('address_city','city');
    $this->_select->addField('address_state','state');
    $this->_select->addField('address_code','postal');

    $this->_select->order_by('dealer.name');

    $this->_criteria = array(
        'dealer.address_latitude <> ?' => '',
        'dealer.address_longitude <> ?' => '',
        'dealer.is_active = ?' => 1,
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

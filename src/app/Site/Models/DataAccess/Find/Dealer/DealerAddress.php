<?php

namespace Site\Models\DataAccess\Find\Dealer;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class DealerAddress extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('dealer');
    $this->_select->addField('id_dealer');
    $this->_select->addField('name');
    $this->_select->addField('address_code');
    $this->_select->addField('address_latitude');
    $this->_select->addField('address_longitude');

    $this->_select->order_by('dealer.name');
    $this->_select->limit(5);

    $this->_criteria = array(
        'dealer.address_code <> ?' => '',
        'OR' => array(
          'dealer.address_latitude IS NULL' => '',
          'dealer.address_longitude IS NULL' => '',
        )
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

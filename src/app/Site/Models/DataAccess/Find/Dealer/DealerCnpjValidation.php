<?php

namespace Site\Models\DataAccess\Find\Dealer;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class DealerCnpjValidation extends AbstractFind
{

  public function __construct ($cnpj)
  {
    parent::__construct();

    $this->_select = new Select2('dealer');
    $this->_select->addField('id_dealer');

    $this->_criteria = array(
        'dealer.cnpj = ?' => $cnpj,
    );

     $this->_select->where(new Criteria($this->_criteria));
  }

  public function validate()
  {
    return $this->_dao->select_count($this->_select);
  }

}

<?php

namespace Site\Models\DataAccess\Find\Migration;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Redirect extends AbstractFind
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    parent::__construct();
    $this->_select = new Select2('migration2014');
    $this->_select->addField('link_new');

  }

  public function setUri ( $uri )
  {
    $this->_criteria = array(
        'link_old = ?' => $uri
    );

  }

  public function findUri ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    $result = $this->_dao->select($this->_select);

    if ( count($result) ) {
      return $result[0]['link_new'];
    }

  }

}

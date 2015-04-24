<?php

namespace Site\Models\DataAccess\Find\Action;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\ActionCatCollection;

class ActionCatList extends AbstractDAOClient
{

  protected $_select;
  protected $_criteria;

  public function __construct ()
  {
    $this->_select = new Select2('action_cat');
    $this->_select->addField('id_action_cat');
    $this->_select->addField('title');
    $this->_select->addField('uri');
    $this->_criteria = array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    );

    $this->_select->order_by('title');

    parent::__construct();

  }

  public function setSecretaryBool ( $secretary )
  {
    if ( $secretary ) {
      $this->_criteria['id_secretary IS NOT NULL'] = null;
    } else {
      $this->_criteria['id_secretary IS NULL'] = null;
    }

  }

  public function setIdSecretary ( $id_secretary )
  {
    $this->_criteria['id_secretary = ?'] = $id_secretary;

  }

  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    $collection = $this->_dao->select_iterator($this->_select, new Entity\ActionCat, new ActionCatCollection);

    return $collection;

  }

}

<?php

namespace Site\Models\DataAccess\Find\CollectionCat;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Nav extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('collection_cat');
    $this->_select->addField('title');
    $this->_select->addField('uri');
    $this->_select->order_by('collection_cat.sequence');

    $this->_criteria = array(
        'collection_cat.is_del = ?' => 0,
        'collection_cat.is_active = ?' => 1,
    );

  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\CollectionCat,
      new \Site\Models\DataAccess\Collection\CollectionCatCollection);

  }

}

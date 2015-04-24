<?php

namespace Site\Models\DataAccess\Find\Product;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class ProductList extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('collection');
    $this->_select->addField('id_collection');
    $this->_select->addField('title');
    $this->_select->addField('uri');
    $this->_select->addField('ref');
    $this->_select->addField('description');
    $this->_select->addField('cover');

    $this->_select->order_by('sequence = 0, sequence, ref');

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

  }

  public function setCategoryId($id)
  {
    $this->_criteria['id_collection_cat = ?'] = $id;
  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\Product,
      new \Site\Models\DataAccess\Collection\ProductCollection);

  }

}

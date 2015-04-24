<?php

namespace Site\Models\DataAccess\Find\Product;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class ProductGallery extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('photo_item');
    $this->_select->addField('file');
    $this->_select->order_by('sequence');

  }

  public function setProductId($id)
  {
    $this->_criteria['id_collection = ?'] = $id;
  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\ProductGallery,
      new \Site\Models\DataAccess\Collection\ProductGalleryCollection);

  }

}

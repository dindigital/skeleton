<?php

namespace Site\Models\DataAccess\Find\Document;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class DocumentCatList extends AbstractFind
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    parent::__construct();
    $this->_select = new Select2('document_cat');
    $this->_select->addField('id_document_cat')
            ->addField('title')
            ->addField('uri')
            ->addField('id_parent')
    ;

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

    $this->_select->order_by('sequence=0, sequence, title');

  }

  public function setUri ( $uri )
  {
    $this->_criteria['uri = ?'] = $uri;

  }

  public function setIdParent ( $id_parent )
  {
    $this->_criteria['id_parent = ?'] = $id_parent;

  }

  /**
   *
   * @return \Site\Models\DataAccess\Collection\EventCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\DocumentCat, new \Site\Models\DataAccess\Collection\DocumentCatCollection);

    return $collection;

  }

  public function getCount ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_count($this->_select);

    return $collection;

  }

}

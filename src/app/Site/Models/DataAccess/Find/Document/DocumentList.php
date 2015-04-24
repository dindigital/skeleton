<?php

namespace Site\Models\DataAccess\Find\Document;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class DocumentList extends AbstractFind
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    parent::__construct();
    $this->_select = new Select2('document');
    $this->_select->addField('id_document')
            ->addField('title')
            ->addField('date')
            ->addField('file')
            ->addField('cover')
    ;

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

    $this->_select->order_by('date DESC, title');

  }

  public function setIdDocumentCat ( $id_document_cat )
  {
    $this->_criteria['id_document_cat = ?'] = $id_document_cat;

  }

  /**
   *
   * @return \Site\Models\DataAccess\Collection\EventCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\Document, new \Site\Models\DataAccess\Collection\DocumentCollection);

    return $collection;

  }

  public function getCount ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_count($this->_select);

    return $collection;

  }

}

<?php

namespace Site\Models\DataAccess\Find\Product;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class ProductRelated extends AbstractFind
{

  protected $_current_id;

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
    $this->_select->group_by('collection.id_collection');
    $this->_select->order_by('RAND()');
    $this->_select->limit(3);

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );
  }

  public function setCurrentId ( $id )
  {
    $this->_current_id = $id;

  }

  protected function getTags ()
  {

    $select = new \Din\DataAccessLayer\Select\Select("r_collection_tag");
    $select->addField('id_tag');
    $select->where(new \Din\DataAccessLayer\Criteria\Criteria(array(
        'id_collection = ?' => $this->_current_id
    )));

    $result = $this->_dao->select($select);

    $tags = array();
    foreach ( $result as $row ) {
      $tags[] = $row['id_tag'];
    }

    return $tags;

  }

  public function prepare ()
  {
    $tags = $this->getTags();

    if ( !count($tags) )
      $tags = array('');

    $this->_select->inner_join('r_collection_tag', 'id_collection', 'id_collection');
    $this->_criteria['r_collection_tag.id_tag IN (?)'] = $tags;
    $this->_criteria['collection.id_collection <> ?'] = $this->_current_id;

    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\Product,
      new \Site\Models\DataAccess\Collection\ProductCollection);

  }

}

<?php

namespace Site\Models\DataAccess\Find\Product;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Product extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('collection');
    $this->_select->addField('id_collection');
    $this->_select->addField('title');
    $this->_select->addField('ref');
    $this->_select->addField('uri');
    $this->_select->addField('description');
    $this->_select->addField('cover');
    $this->_select->addField('size');
    $this->_select->addField('color');
    $this->_select->addField('content');

    $this->_select->inner_join('collection_cat', 'id_collection_cat', 'id_collection_cat');
    $this->_select->addField('title', 'category_title', 'collection_cat');
    $this->_select->addField('uri', 'category_uri', 'collection_cat');

    $this->_select->limit(1);

    $this->_criteria = array(
        'collection.is_del = ?' => 0,
        'collection.is_active = ?' => 1,
        'collection_cat.is_del = ?' => 0,
        'collection_cat.is_active = ?' => 1,
    );

  }

  public function setUri ( $uri )
  {
    $this->_criteria['collection.uri = ?'] = $uri;

  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getEntity ()
  {
    $result = $this->_dao->select($this->_select, new \Site\Models\DataAccess\Entity\Product);

    if ( !count($result) )
      throw new \Site\Models\DataAccess\Find\Exception\ContentNotFoundException('Conteúdo não encontrado.');

    return $result[0];

  }

}

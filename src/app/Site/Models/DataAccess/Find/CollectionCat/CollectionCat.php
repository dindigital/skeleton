<?php

namespace Site\Models\DataAccess\Find\CollectionCat;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class CollectionCat extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('collection_cat');
    $this->_select->addField('id_collection_cat');
    $this->_select->addField('title');
    $this->_select->addField('uri');

    $this->_select->limit(1);

    $this->_criteria = array(
        'collection_cat.is_del = ?' => 0,
        'collection_cat.is_active = ?' => 1,
    );

  }

  public function setUri ( $uri )
  {
    $this->_criteria['collection_cat.uri = ?'] = $uri;

  }

  public function prepare ()
  {
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getEntity ()
  {
    $result = $this->_dao->select($this->_select, new \Site\Models\DataAccess\Entity\CollectionCat);

    if ( !count($result) )
      throw new \Site\Models\DataAccess\Find\Exception\ContentNotFoundException('Conteúdo não encontrado.');

    return $result[0];

  }

}

<?php

namespace Site\Models\DataAccess\Find\RSS;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Union;
use Site\Models\DataAccess\Entity;
use Site\Models\DataAccess\Collection;

class RSS extends AbstractFind
{

  protected $_areas;

  public function addArea ( AreaInterface $area )
  {
    $this->_areas[] = $area;

  }

  public function getResult ()
  {
    $select = $this->getUnionSelect();

    $result = $this->_dao->select_iterator($select, new Entity\RSSResult, new Collection\RSSResultCollection);

    return $result;

  }

  protected function getUnionSelect ()
  {
    $select = new Union();

    foreach ( $this->_areas as $area ) {
      if ( !$area instanceof AreaInterface )
        throw new \Exception('Area must implement AreaInterface');

      $select->addSelect($area->getSelect());
    }

    $select->limit(20);
    $select->order_by('date DESC');

    return $select;

  }

}

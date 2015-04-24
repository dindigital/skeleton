<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Entity;
use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Union;
use Site\Models\DataAccess\Collection;

class NewsList extends AbstractFind
{

  protected $_areas;

  public function addArea ( AreaInterface $area )
  {
    $this->_areas[] = $area;

  }

  /**
   *
   * @return int
   */
  public function getCount ()
  {
    return $this->_dao->select_count($this->getUnionSelect());

  }

  /**
   *
   * @return SearchResultCollection
   */
  public function getList ()
  {
    $select = $this->getUnionSelect();
    $select->limit($this->_limit, $this->_offset);

    $result = $this->_dao->select_iterator($select, new Entity\News, new Collection\NewsCollection);

    return $result;

  }

  protected function getUnionSelect ()
  {
    $select = new Union();

    foreach ( $this->_areas as $area ) {
      if ( !$area instanceof AreaInterface )
        throw new \Exception('Area must implement AreaInterface');

      $area->setTerm($this->_term);
      $area->setSkipId($this->_skip_ids);

      $select->addSelect($area->getSelect());
    }

    $select->order_by('date DESC');

    return $select;

  }

}

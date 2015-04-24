<?php

namespace Site\Models\DataAccess\Find\Sitemap;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Find\Sitemap\AreaInterface;
use Site\Models\DataAccess\Entity;
use Site\Models\DataAccess\Collection;

class Sitemap extends AbstractFind
{

  protected $_area;

  public function __construct ( AreaInterface $area )
  {
    parent::__construct();
    $this->_area = $area;

  }

  /**
   *
   * @return Collection\SitemapCollection
   */
  public function getCollection ()
  {
    $select = $this->_area->getSelect();

    $collection = $this->_dao->select_iterator($select, new Entity\Sitemap, new Collection\SitemapCollection);

    return $collection;

  }

}

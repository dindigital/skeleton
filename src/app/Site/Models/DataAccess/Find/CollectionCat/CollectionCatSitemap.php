<?php

namespace Site\Models\DataAccess\Find\CollectionCat;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class CollectionCatSitemap extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('collection_cat');
    $this->_select->addField('uri', 'loc');
    $this->_select->addField('inc_date', 'lastmod');

    $this->_select->order_by('inc_date DESC');

    $this->_criteria = array(
        'collection_cat.is_del = ?' => 0,
        'collection_cat.is_active = ?' => 1,
    );

  }

  /**
   *
   * @return \Site\Models\DataAccess\Collection\SitemapCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    return $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\Sitemap, new \Site\Models\DataAccess\Collection\SitemapCollection);

  }

}

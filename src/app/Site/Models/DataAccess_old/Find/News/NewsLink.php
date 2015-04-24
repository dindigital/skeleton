<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\NewsLinkCollection;
use Site\Models\DataAccess\Find\AbstractFind;

class NewsLink extends AbstractFind
{

  protected $_criteria = array();

  public function setIdNews ( $id_news )
  {
    $this->_criteria['id_news = ?'] = $id_news;

  }

  /**
   *
   * @return NewsLinkCollection
   */
  public function getCollection ()
  {
    $select = new Select2('news_link');
    $select->addField('title');
    $select->addField('url');

    $select->where(new Criteria($this->_criteria));

    $select->order_by('sequence');

    $result = $this->_dao->select_iterator($select, new Entity\NewsLink, new NewsLinkCollection);

    return $result;

  }

}

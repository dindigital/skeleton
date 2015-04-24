<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\NewsCollection;
use Site\Models\DataAccess\Find\AbstractFind;

class NewsFull extends AbstractFind
{

  protected $_criteria = array();

  /**
   *
   * @param string $type @see NewsType
   */
  public function setUri ( $uri )
  {
    $this->_criteria['uri = ?'] = $uri;

  }

  /**
   *
   * @param string $uri
   * @return NewsCollection
   */
  public function getNews ()
  {
    $select = new Select2('news');
    $select->addField('id_news');
    $select->addField('uri');
    $select->addField('short_link');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('head');
    $select->addField('body');
    $select->addField('description');
    $select->addField('keywords');
    $select->addField('author');
    $select->addField('upd_date');
    $select->addField('type');
    $select->addField('cover_legend');
    $select->addField('cover_credit');
    $select->addField('quote_text');
    $select->addField('quote_credit');


    $select->where(new Criteria(array_merge($this->_criteria, array(
                'is_active = ?' => 1,
                'is_del = ?' => 0,
    ))));

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\News, new NewsCollection);

    return $result;

  }

}

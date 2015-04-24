<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\NewsCollection;
use Site\Models\DataAccess\Find\AbstractFind;

class News extends AbstractFind
{

  protected $_criteria = array();

  /**
   *
   * @param string $type @see NewsType
   */
  public function setType ( $type )
  {
    $this->_criteria['type = ?'] = $type;

  }

  /**
   *
   * @param \DateTime $home_exp
   */
  public function setHomeExp ( \DateTime $home_exp )
  {
    $this->_criteria['OR'] = array(
        'home_exp > ?' => $home_exp->format('Y-m-d H:i:s'),
        'home_exp IS NULL' => null
    );

  }

  public function setSkipId ( array $ids )
  {
    if ( count($ids) ) {
      $this->_criteria['id_news NOT IN (?)'] = $ids;
    }

  }

  public function setTerm ( $term )
  {
    parent::setTerm($term);
    $this->_criteria['OR'] = array(
        'title LIKE ?' => $this->_term_like,
        'description LIKE ?' => $this->_term_like,
        'keywords LIKE ?' => $this->_term_like,
    );

  }

  private function getSelect ()
  {
    $select = new Select2('news');
    $select->addField('id_news');
    $select->addField('title');
    $select->addField('title_home');
    $select->addField('uri');
    $select->addField('cover');
    $select->addField('date');
    $select->addField('head');

    $select->where(new Criteria(array_merge($this->_criteria, array(
                'is_active = ?' => 1,
                'is_del = ?' => 0,
    ))));

    return $select;

  }

  /**
   *
   * @return NewsCollection
   */
  public function getList ()
  {
    $select = $this->getSelect();
    $select->order_by($this->_order);

    $select->limit($this->_limit, $this->_offset);

    $result = $this->_dao->select_iterator($select, new Entity\News, new NewsCollection);

    return $result;

  }

  /**
   *
   * @return int
   */
  public function getCount ()
  {
    $select = $this->getSelect();

    return $this->_dao->select_count($select);

  }

}

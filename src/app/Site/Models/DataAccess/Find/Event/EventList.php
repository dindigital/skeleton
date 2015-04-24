<?php

namespace Site\Models\DataAccess\Find\Event;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class EventList extends AbstractFind
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    parent::__construct();
    $this->_select = new Select2('event');
    $this->_select->addField('id_event')
            ->addField('title')
            ->addField('start_date')
            ->addField('end_date')
            ->addField('uri')
            ->addField('cover')
            ->addField('description')
            ->addField('place')
            ->addField('is_live')
    ;

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

    $this->_select->order_by('start_date ASC');

  }

  public function setTerm ( $term )
  {
    $term_like = "%{$term}%";

    $this->_criteria['OR'] = array(
        'title LIKE ?' => $term_like,
        'keywords LIKE ?' => $term_like,
        'description LIKE ?' => $term_like,
    );

  }

  public function isSpotlight ( $bool )
  {
    $this->_criteria['is_spotlight = ?'] = intval($bool);

  }

  public function setStartMonth ( $year, $month )
  {
    $this->_criteria['start_date >= ?'] = date('Y-m-d', strtotime("{$year}-{$month}-01"));
    $this->_criteria['start_date <= ?'] = date('Y-m-d', strtotime("{$year}-{$month}-01 +1month -1day"));

  }

  public function setLimit ( $limit )
  {
    parent::setLimit($limit);

    $this->_select->limit($limit);

  }

  public function setDate ( $date )
  {
    $this->_criteria['start_date <= ?'] = $date;
    $this->_criteria['end_date >= ?'] = $date;

  }

  public function setIdSecretary ( $id_secretary )
  {
    $this->_criteria['id_secretary = ?'] = $id_secretary;

  }

  /**
   *
   * @return \Site\Models\DataAccess\Collection\EventCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\Event, new \Site\Models\DataAccess\Collection\EventCollection);

    return $collection;

  }

  public function getCount ()
  {
    $this->_select->where(new Criteria($this->_criteria));
    $collection = $this->_dao->select_count($this->_select);

    return $collection;

  }

}

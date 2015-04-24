<?php

namespace Site\Models\DataAccess\Find\Event;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Event extends AbstractFind
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
            ->addField('content')
            ->addField('uri')
            ->addField('cover')
            ->addField('description')
            ->addField('keywords')
            ->addField('place')
            ->addField('is_live')
    ;

    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

  }

  public function setUri ( $uri )
  {
    $this->_criteria['uri = ?'] = $uri;

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

}

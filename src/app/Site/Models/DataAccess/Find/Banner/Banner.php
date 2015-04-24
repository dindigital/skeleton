<?php

namespace Site\Models\DataAccess\Find\Banner;

use Site\Models\DataAccess\Find\AbstractFind;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Banner extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('banner');
    $this->_select->addField('title');
    $this->_select->addField('target');
    $this->_select->addField('url');
    $this->_select->addField('file');
    $this->_select->order_by('sequence');
    $this->_criteria = array(
        'is_del = ?' => 0,
        'is_active = ?' => 1,
    );

  }

  public function setPosition($position)
  {
    $this->_criteria['position = ?'] = $position;
  }

  public function prepare ()
  {
    $this->_select->limit($this->_limit);
    $this->_select->where(new Criteria($this->_criteria));

  }

  public function getCollection ()
  {
    return $this->_dao->select_iterator($this->_select,
      new \Site\Models\DataAccess\Entity\Banner,
      new \Site\Models\DataAccess\Collection\BannerCollection);

  }

}

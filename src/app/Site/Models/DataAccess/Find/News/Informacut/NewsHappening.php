<?php

namespace Site\Models\DataAccess\Find\News\Informacut;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\NewsCollection;

class NewsHappening extends AbstractDAOClient
{

  public function getCollection ()
  {

    $select = new Select2('news');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');
    $select->addField('cover');
    $select->addField('head');
    $select->order_by('sequence=0, sequence, date DESC');
    $select->limit(2);

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'type = ?' => 'happening'
    )));

    $result = $this->_dao->select_iterator($select, new Entity\News, new NewsCollection);

    return $result;

  }

}

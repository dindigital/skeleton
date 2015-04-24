<?php

namespace Site\Models\DataAccess\Find\Logo;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\LogoCollection;

class Logo extends AbstractDAOClient
{

    /**
     * 
     * @return LogoCollection
     */
  public function getAll ()
  {
    $select = new Select2('logo');
    $select->addField('id_logo');
    $select->addField('title');
    $select->addField('file');
    $select->addField('url');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $select->order_by('sequence');

    $collection = $this->_dao->select_iterator($select, new Entity\Logo, new LogoCollection);

    return $collection;
  }

}

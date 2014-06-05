<?php

namespace Site\Models\DataAccess;

use Site\Models\DataAccess\AbstractDataAccess;
use Din\DataAccessLayer\Select;
use Site\Models\Entities;

class Settings extends AbstractDataAccess
{

  public function getSettings ()
  {

    $select = new Select('settings');
    $select->addField('home_title');
    $select->addField('home_description');
    $select->addField('home_keywords');
    $select->addField('title');
    $select->addField('description');
    $select->addField('keywords');
    $select->limit(1);

    $result = $this->_dao->select($select, new Entities\Settings);

    return $result;

  }

}

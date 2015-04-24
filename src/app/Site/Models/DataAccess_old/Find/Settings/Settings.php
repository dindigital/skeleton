<?php

namespace Site\Models\DataAccess\Find\Settings;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;

class Settings extends AbstractDAOClient
{

  public function getAll ()
  {
    $select = new Select2('settings');
    $select->addAllFields();

    $result = $this->_dao->select($select, new Entity\Settings);

    return $result;

  }

}

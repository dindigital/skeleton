<?php

namespace Site\Models\DataAccess\Find\Settings;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;

class SocialCredentials extends AbstractDAOClient
{

  public function getAll ()
  {
    $select = new Select2('socialmedia_credentials');
    $select->addAllFields();

    $result = $this->_dao->select($select, new Entity\SocialCredentials);

    return $result;

  }

}

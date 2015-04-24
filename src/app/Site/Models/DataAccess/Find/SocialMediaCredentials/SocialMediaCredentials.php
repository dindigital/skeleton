<?php

namespace Site\Models\DataAccess\Find\SocialMediaCredentials;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;

class SocialMediaCredentials extends AbstractDAOClient
{

  protected $_select;

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('socialmedia_credentials');
    $this->_select->addAllFields();

  }

  /**
   *
   * @throws \Site\Models\DataAccess\Find\Exception\EmptyTableException
   * @return Entity\SocialMediaCredentials
   */
  public function getEntity ()
  {
    $result = $this->_dao->select($this->_select, new Entity\SocialMediaCredentials);

    if ( !count($result) )
      throw new \Site\Models\DataAccess\Find\Exception\EmptyTableException('Tablea de mídias sociais está vazia');

    return $result[0];

  }

}

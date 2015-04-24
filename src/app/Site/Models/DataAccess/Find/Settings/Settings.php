<?php

namespace Site\Models\DataAccess\Find\Settings;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;

class Settings extends AbstractDAOClient
{

  protected $_select;

  public function __construct ()
  {
    parent::__construct();

    $this->_select = new Select2('settings');
    $this->_select->addAllFields();

  }

  /**
   *
   * @throws \Site\Models\DataAccess\Find\Exception\EmptyTableException
   * @return Entity\Settings
   */
  public function getEntity ()
  {
    $result = $this->_dao->select($this->_select, new Entity\Settings);

    if ( !count($result) )
      throw new \Site\Models\DataAccess\Find\Exception\EmptyTableException('Tablea de configurações está vazia');

    return $result[0];

  }

}

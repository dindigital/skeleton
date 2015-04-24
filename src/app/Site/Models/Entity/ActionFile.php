<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;
use Site\Models\DataAccess\Collection\ActionFileVersionCollection;

class ActionFile extends AbstractEntity
{

  protected $_version;

  public function getIdActionFile ()
  {
    return $this->getField('id_action_file');

  }

  /**
   *
   * @param \Site\Models\DataAccess\Collection\ActionFileVersionCollection $version
   */
  public function setVersion ( ActionFileVersionCollection $version )
  {
    $this->_version = $version;

  }

  /**
   *
   * @return ActionFileVersionCollection
   */
  public function getVersion ()
  {
    return $this->_version;

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getInformation ()
  {
    return $this->getField('information');

  }

}

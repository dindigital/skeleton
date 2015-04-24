<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;
use Site\Models\DataAccess\Collection\ActionCollectionInterface;

class ActionCat extends AbstractEntity
{

  protected $_action;
  protected $_active = false;

  public function getIdActionCat ()
  {
    return $this->getField('id_action_cat');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function setAction ( ActionCollectionInterface $action )
  {
    $this->_action = $action;

  }

  /**
   *
   * @return ActionCollectionInterface
   */
  public function getAction ()
  {
    return $this->_action;

  }

  public function setActive ( $active )
  {
    $this->_active = $active;

  }

  public function getActive ()
  {
    return $this->_active;

  }

}

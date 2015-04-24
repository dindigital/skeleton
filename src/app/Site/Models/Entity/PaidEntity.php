<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class PaidEntity extends AbstractEntity
{

  public function getName ()
  {
    return $this->getField('name');

  }

  public function getAcronym ()
  {
    return $this->getField('acronym');

  }

  public function getCity ()
  {
    return $this->getField('city');

  }

  public function getState ()
  {
    return $this->getField('state');

  }

  public function getInstance ()
  {
    return $this->getField('instance');

  }

  public function getCod ()
  {
    return $this->getField('cod');

  }

  public function getDueDate ()
  {
    return $this->getField('due_date');

  }

  public function getUpdDate ()
  {
    return $this->getField('upd_date');

  }

}

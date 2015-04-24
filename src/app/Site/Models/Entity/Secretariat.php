<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Secretariat extends AbstractEntity
{

  public function getIdSecretary ()
  {
    return $this->getField('id_secretary');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getPowers ()
  {
    return $this->getField('powers');

  }

}

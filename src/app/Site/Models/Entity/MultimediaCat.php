<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class MultimediaCat extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getFriendly ()
  {
    return $this->getField('friendly');

  }

}

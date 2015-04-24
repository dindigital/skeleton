<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Tag extends AbstractEntity
{

  public function getIdTag ()
  {
    return $this->getField('id_video');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getSize ()
  {
    return $this->getField('size');

  }

}

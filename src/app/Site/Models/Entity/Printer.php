<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Printer extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

}

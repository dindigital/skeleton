<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Banner extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUrl ()
  {
    return $this->getField('url');

  }

  public function getTarget ()
  {
    return $this->getField('target');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

}

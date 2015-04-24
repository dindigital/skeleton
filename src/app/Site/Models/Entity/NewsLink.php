<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class NewsLink extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getUrl ()
  {
    return $this->getField('url');

  }

}

<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\Decorators\AbstractMetatags;
use Site\Models\Entities\Decorators\NavInterface;

class PageNav extends AbstractMetatags implements NavInterface
{

  public function getLink ()
  {
    return $this->getUrl() ? $this->getUrl() : $this->getUri();

  }

  public function getTarget ()
  {
    return $this->_entity->getTarget();

  }

}

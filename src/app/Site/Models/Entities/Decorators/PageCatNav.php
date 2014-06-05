<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\Decorators\AbstractMetatags;
use Site\Models\Entities\Decorators\NavInterface;

class PageCatNav extends AbstractMetatags implements NavInterface
{

  public function getLink ()
  {
    return $this->getUrl() ? $this->getUrl() : $this->getUri();

  }

  public function getTarget ()
  {
    return $this->_entity->getTarget();

  }

  public function getClass ( $uri, $has_drop )
  {
    $class = $this->getLink() == $uri ? 'is_active' : '';
    $class .= $has_drop ? ' submenu_header' : '';

    return $class;

  }

}

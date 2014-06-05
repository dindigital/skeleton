<?php

namespace Site\Models\Entities\Decorators;

use Din\Image\Picuri;
use Site\Models\Entities\Decorators\AbstractDecorator;
use Din\Filters\Date\DateFormat;

class NewsList extends AbstractDecorator
{

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate());

  }

  public function getCover ()
  {
    $cover = $this->_entity->getCover();
    if ( !is_null($cover) ) {
      return Picuri::picUri($cover, 40, 40, true);
    }

  }

}

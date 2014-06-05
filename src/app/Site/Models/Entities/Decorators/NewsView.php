<?php

namespace Site\Models\Entities\Decorators;

use Site\Models\Entities\Decorators\AbstractMetatags;
use Din\Filters\Date\DateFormat;

class NewsView extends AbstractMetatags
{

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate());

  }

  public function getKeywords ()
  {
    if ( $this->_entity->getKeywords() ) {
      $ex = explode(',', $this->_entity->getKeywords());
      $keywords = implode(', ', $ex);
    } else {
      $keywords = null;
    }
    return $keywords;

  }

  public function getImage ()
  {
    return $this->_entity->getCover();

  }

  public function getUrl ()
  {
    return URL . $this->_entity->getUri();

  }

}

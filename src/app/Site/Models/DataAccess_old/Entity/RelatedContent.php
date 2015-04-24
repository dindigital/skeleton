<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class RelatedContent extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getAreaName ()
  {
    return $this->getField('area_name');

  }

}

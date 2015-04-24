<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class ProductGallery extends AbstractEntity
{

  public function getFile ()
  {
    return $this->getField('file');

  }

}

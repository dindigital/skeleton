<?php

namespace Site\Models\DataAccess\Entity;

class Sitemap extends AbstractEntity
{

  public function getLoc ()
  {
    return $this->getField('loc');

  }

  public function getLastmod ()
  {
    return $this->getField('lastmod');

  }

}

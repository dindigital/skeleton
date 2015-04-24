<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Sitemap extends AbstractEntity
{

  public function getLoc ()
  {
    return URL . $this->getField('uri');

  }

  public function getLastmod ()
  {
    return date('Y-m-d\TH:i:s\-\0\3\:\0\0', strtotime($this->getField('lastmod')));

  }

}

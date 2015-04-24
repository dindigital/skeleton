<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Page extends AbstractEntity
{

  public function getIdPage ()
  {
    return $this->getField('id_page');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getContent ()
  {
    return $this->getField('content');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getKeywords ()
  {
    return $this->getField('keywords');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getArea ()
  {
    return $this->getField('area');

  }

}

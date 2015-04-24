<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class SearchResult extends AbstractEntity
{

  public function getArea ()
  {
    return $this->getField('area');

  }

  public function getId ()
  {
    return $this->getField('id');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getHead ()
  {
    return $this->getField('head');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getIdYoutube ()
  {
    return $this->getField('id_youtube');

  }

}

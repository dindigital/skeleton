<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Product extends AbstractEntity
{

  public function getId ()
  {
    return $this->getField('id_collection');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getRef ()
  {
    return $this->getField('ref');

  }

  public function getDescription ()
  {
    return $this->getField('description');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getCategoryTitle()
  {
    return $this->getField('category_title');

  }

  public function getCategoryUri()
  {
    return $this->getField('category_uri');

  }

  public function getSize()
  {
    return $this->getField('size');

  }

  public function getColor()
  {
    return $this->getField('color');

  }

  public function getContent()
  {
    return $this->getField('content');

  }

}

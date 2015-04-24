<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Multimedia extends AbstractEntity
{

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

  public function getCategory ()
  {
    return $this->getField('category');

  }

  public function getTbl ()
  {
    return $this->getField('tbl');

  }

  public function getIdYoutube ()
  {
    return $this->getField('id_youtube');

  }

}

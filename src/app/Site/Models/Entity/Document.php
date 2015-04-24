<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class Document extends AbstractEntity
{

  public function getIdDocument ()
  {
    return $this->getField('id_document');

  }

  public function getTitle ()
  {
    return $this->getField('title');

  }

  public function getDate ()
  {
    return $this->getField('date');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

  public function getCover ()
  {
    return $this->getField('cover');

  }

}

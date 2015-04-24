<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;

class PhotoItem extends AbstractEntity
{

  public function getIdPhotoItem ()
  {
    return $this->getField('id_photo_item');

  }

  public function getFile ()
  {
    return $this->getField('file');

  }

  public function getLabel ()
  {
    return $this->getField('label');

  }

  public function getCredit ()
  {
    return $this->getField('credit');

  }

  public function getPlace ()
  {
    return $this->getField('place');

  }

}

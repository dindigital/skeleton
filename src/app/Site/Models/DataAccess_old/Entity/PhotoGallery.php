<?php

namespace Site\Models\DataAccess\Entity;

use Site\Models\DataAccess\Entity\AbstractEntity;
use Site\Models\DataAccess\Entity\PhotoItemCollectionInterface;

class PhotoGallery extends AbstractEntity
{

  protected $_photo_item;

  public function setPhotoItem ( PhotoItemCollectionInterface $photo_item )
  {
    $this->_photo_item = $photo_item;

  }

  public function getPhotoItem ()
  {
    return $this->_photo_item;

  }

  public function getIdPhoto ()
  {
    return $this->getField('id_photo');

  }

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

  public function getUri ()
  {
    return $this->getField('uri');

  }

  public function getShortLink ()
  {
    return $this->getField('short_link');

  }

  public function getUpdDate ()
  {
    return $this->getField('upd_date');

  }

}

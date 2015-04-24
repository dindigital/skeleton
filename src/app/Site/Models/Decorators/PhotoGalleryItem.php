<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\PhotoItem;
use Din\Image\Picuri;
use Din\Filters\String\Html;

class PhotoGalleryItem extends AbstractDecorator
{

  public function __construct ( PhotoItem $entity )
  {
    parent::__construct($entity);

  }

  public function getLabel ()
  {
    return Html::scape($this->_entity->getLabel());

  }

  public function getPlace ()
  {
    return Html::scape($this->_entity->getPlace());

  }

  public function getFileUrl ()
  {
    return URL . $this->_entity->getFile();

  }

  public function getDownloadMedium ()
  {
    return '/download/photo_item/' . $this->_entity->getIdPhotoItem() . '/?size=800';

  }

  public function getDownloadHigh ()
  {
    return '/download/photo_item/' . $this->_entity->getIdPhotoItem();

  }

  public function getFile ()
  {
    $picuri = new Picuri($this->_entity->getFile());
    $picuri->setWidth(810);
    $picuri->setHeight(540);
    $picuri->setCrop(false);
    $picuri->setTypeReturn('path');

    $picuri->save();

    return $picuri->getImage();

  }

  public function getFileMini ()
  {
    $picuri = new Picuri($this->_entity->getFile());
    $picuri->setWidth(80);
    $picuri->setHeight(80);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('path');

    $picuri->save();

    return $picuri->getImage();

  }

}

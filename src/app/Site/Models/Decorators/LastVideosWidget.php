<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\VideoGallery;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\String\LimitChars;

class LastVideosWidget extends AbstractDecorator
{

  public function __construct ( VideoGallery $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape(LimitChars::filter($this->_entity->getTitle(), 100));

  }

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(226);
    $picuri->setHeight(110);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle()
    ));

    $picuri->save();

    return $picuri->getImage();

  }

}

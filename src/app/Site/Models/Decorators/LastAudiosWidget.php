<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\AudioGallery;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\String\LimitChars;

class LastAudiosWidget extends AbstractDecorator
{

  public function __construct ( AudioGallery $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape(LimitChars::filter($this->_entity->getTitle(), 100));

  }

  protected function getCoverUri ()
  {
    if ( !is_null($this->_entity->getCover()) )
      return $this->_entity->getCover();

    return '/site/img/img_audio_sample.png';

  }

  public function getCover ()
  {
    $picuri = new Picuri($this->getCoverUri());
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

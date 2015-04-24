<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\News;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\String\LimitChars;

class LastNewsWidget extends AbstractDecorator
{

  public function __construct ( News $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape(LimitChars::filter($this->_entity->getTitle(), 100));

  }

  public function getCover ()
  {
    if ( is_null($this->_entity->getCover()) )
      return null;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(70);
    $picuri->setHeight(70);
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

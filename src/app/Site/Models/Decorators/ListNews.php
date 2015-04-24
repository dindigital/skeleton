<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\News;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;

class ListNews extends AbstractDecorator
{

  public function __construct ( News $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDate ()
  {
    return DateFormat::filter_date($this->_entity->getDate(), 'd/m/Y - H:i');

  }

  public function getCover ()
  {
    if ( is_null($this->_entity->getCover()) )
      return null;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(90);
    $picuri->setHeight(70);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle()
    ));

    $picuri->save();

    return $picuri->getImage();

  }

  public function getHead ()
  {
    return \Din\Filters\String\LimitChars::filter($this->_entity->getHead(), 100);

  }

}

<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\News;
use Din\Image\Picuri;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;

class NewsListSpotlight extends AbstractDecorator
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
    $date = $this->_entity->getDate();
    return DateFormat::filter_date($date);

  }

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(350);
    $picuri->setHeight(170);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle()
    ));

    $picuri->save();

    return $picuri->getImage();

  }

}

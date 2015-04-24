<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\RSSResult;
use Din\Image\Picuri;
use Din\Filters\String\Html;
use Din\Filters\Date\DateFormat;

class RSSResultDecorator extends AbstractDecorator
{

  public function __construct ( RSSResult $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getDate ()
  {
    return date('Y-m-d\TH:i:s\-\0\3\:\0\0', strtotime($this->_entity->getDate()));

  }

  public function getDescription ()
  {
    return Html::scape($this->_entity->getHead());

  }

  public function getCover ()
  {
    if ( is_null($this->_entity->getCover()) )
      return;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(90);
    $picuri->setHeight(68);
    $picuri->setCrop(true);
    $picuri->setCropType('center');
    $picuri->setTypeReturn('path');

    $picuri->save();

    return '<![CDATA[<a href="' . $this->getUrl() . '"><img src="' . $picuri->getImage() . '" /></a>]]>';

  }

  public function getUrl ()
  {
    return URL . $this->_entity->getUri();

  }

}

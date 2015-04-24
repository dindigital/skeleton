<?php

namespace Site\Models\Decorators;

use Din\Image\Picuri;

class NewsSpotlightInformacut extends HomeSpotlight
{

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(330);
    $picuri->setHeight(200);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('path');

    $picuri->save();

    return URL . $picuri->getImage();

  }

  public function getUri ()
  {
    return URL . $this->_entity->getUri();
  }

}

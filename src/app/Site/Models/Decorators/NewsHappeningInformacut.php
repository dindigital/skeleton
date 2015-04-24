<?php

namespace Site\Models\Decorators;

use Din\Image\Picuri;

class NewsHappeningInformacut extends HomeHappening
{

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(240);
    $picuri->setHeight(150);
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

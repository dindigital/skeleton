<?php

namespace Site\Models\Decorators;

use Din\Image\Picuri;

class NewsCommonInformacut extends HomeCommonNews
{

  public function getCover ()
  {
    if (is_null($this->_entity->getCover()))
      return null;

    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(90);
    $picuri->setHeight(70);
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

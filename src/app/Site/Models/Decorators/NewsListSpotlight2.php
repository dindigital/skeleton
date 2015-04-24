<?php

namespace Site\Models\Decorators;

use Din\Image\Picuri;

class NewsListSpotlight2 extends NewsListSpotlight
{

  public function getCover ()
  {
    $picuri = new Picuri($this->_entity->getCover());
    $picuri->setWidth(225);
    $picuri->setHeight(138);
    $picuri->setCrop(true);
    $picuri->setTypeReturn('tag_without_size');
    $picuri->setAtributos(array(
        'alt' => $this->getTitle()
    ));

    $picuri->save();

    return $picuri->getImage();

  }

}

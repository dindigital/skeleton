<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\NewsLink;
use Din\Filters\String\Html;

class NewsLinkDecorator extends AbstractDecorator
{

  public function __construct ( NewsLink $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

}

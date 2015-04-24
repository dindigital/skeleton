<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\MultimediaCat;
use Din\Filters\String\Html;

class MultimediaCatList extends AbstractDecorator
{

  public function __construct ( MultimediaCat $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getFriendly ()
  {
    return Html::scape($this->_entity->getFriendly());

  }

}

<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\Tag;
use Din\Filters\String\Html;

class TagCloudDecorator extends AbstractDecorator
{

  public function __construct ( Tag $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  public function getUri ()
  {
    return '/busca/?t=' . $this->_entity->getTitle();

  }

  public function getSize ()
  {
    $n = $this->_entity->getSize() == 100 ? 90 : $this->_entity->getSize();
    $n /= 10;

    $n = substr($n, 0, 1);

    return $n;

  }

}

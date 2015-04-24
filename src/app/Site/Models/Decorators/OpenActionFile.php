<?php

namespace Site\Models\Decorators;

use Site\Models\Decorators\AbstractDecorator;
use Site\Models\DataAccess\Entity\ActionFile;
use Site\Models\DataAccess\Collection\Decorators\ActionFileVersionCollection;
use Din\Filters\String\Html;

class OpenActionFile extends AbstractDecorator
{

  public function __construct ( ActionFile $entity )
  {
    parent::__construct($entity);

  }

  public function getTitle ()
  {
    return Html::scape($this->_entity->getTitle());

  }

  /**
   *
   * @return ActionFileVersionCollection
   */
  public function getVersion ()
  {
    return new ActionFileVersionCollection($this->_entity->getVersion());

  }

}

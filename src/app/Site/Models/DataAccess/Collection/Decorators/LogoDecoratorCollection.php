<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\LogoCollection;
use Site\Models\DataAccess\Entity\Decorators\LogoDecorator;

class LogoDecoratorCollection extends AbstractCollectionDecorator
{

  protected $_collection;

  public function __construct ( LogoCollection $collection )
  {
    $this->_collection = $collection;

  }

  public function current ()
  {
    $return = parent::current();

    return new LogoDecorator($return);

  }

}

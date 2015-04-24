<?php

namespace Site\Models\DataAccess\Collection\Decorators\Product;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;

class ProductListCollectionDecorator extends AbstractCollectionDecorator
{

  public function __construct ( \Site\Models\DataAccess\Collection\ProductCollection $collection )
  {
    $this->_collection = $collection;

  }

  public function current ()
  {
    return new \Site\Models\DataAccess\Entity\Decorators\ProductListDecorator(parent::current());

  }

}

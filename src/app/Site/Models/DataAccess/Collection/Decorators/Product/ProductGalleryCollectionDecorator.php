<?php

namespace Site\Models\DataAccess\Collection\Decorators\Product;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;

class ProductGalleryCollectionDecorator extends AbstractCollectionDecorator
{

  public function __construct ( \Site\Models\DataAccess\Collection\ProductGalleryCollection $collection )
  {
    $this->_collection = $collection;

  }

  public function current ()
  {
    return new \Site\Models\DataAccess\Entity\Decorators\ProductGalleryDecorator(parent::current());

  }

}

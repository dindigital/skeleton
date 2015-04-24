<?php

namespace Site\Models\DataAccess\Collection\Decorators;

class SitemapCollectionDecorator extends AbstractCollectionDecorator
{

  public function __construct ( \Site\Models\DataAccess\Collection\SitemapCollection $collection )
  {
    $this->_collection = $collection;

  }

  public function current ()
  {
    return new \Site\Models\DataAccess\Entity\Decorators\SitemapDecorator(parent::current());

  }

}

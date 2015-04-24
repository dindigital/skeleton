<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\FeaturedNewsCarouselCollection;
use Site\Models\DataAccess\Entity\Decorators\FeaturedNewsCarouselDecorator;

class FeaturedNewsCarouselDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( FeaturedNewsCarouselCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new FeaturedNewsCarouselDecorator($return);

    }

}

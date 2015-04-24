<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\HomeNewsCollection;
use Site\Models\DataAccess\Entity\Decorators\HomeNewsDecorator;

class HomeNewsDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( HomeNewsCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new HomeNewsDecorator($return);

    }

}

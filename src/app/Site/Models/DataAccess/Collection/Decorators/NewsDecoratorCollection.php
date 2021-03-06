<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\NewsCollection;
use Site\Models\DataAccess\Entity\Decorators\NewsDecorator;

class NewsDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( NewsCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new NewsDecorator($return);

    }

}

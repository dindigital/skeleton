<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\ProcessDetailsCollection;
use Site\Models\DataAccess\Entity\Decorators\ProcessDetailsDecorator;

class ProcessDetailsDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( ProcessDetailsCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new ProcessDetailsDecorator($return);

    }

}

<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\ClientStatusCollection;
use Site\Models\DataAccess\Entity\Decorators\ClientStatusDecorator;

class ClientStatusDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( ClientStatusCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new ClientStatusDecorator($return);

    }

}

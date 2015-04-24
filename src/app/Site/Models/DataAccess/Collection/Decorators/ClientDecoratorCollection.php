<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\ClientCollection;
use Site\Models\DataAccess\Entity\Decorators\ClientDecorator;

class ClientDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( ClientCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new ClientDecorator($return);

    }

}

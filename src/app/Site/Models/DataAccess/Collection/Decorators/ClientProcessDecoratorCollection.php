<?php

namespace Site\Models\DataAccess\Collection\Decorators;

use Site\Models\DataAccess\Collection\Decorators\AbstractCollectionDecorator;
use Site\Models\DataAccess\Collection\ClientProcessCollection;
use Site\Models\DataAccess\Entity\Decorators\ClientProcessDecorator;

class ClientProcessDecoratorCollection extends AbstractCollectionDecorator
{

    protected $_collection;

    public function __construct ( ClientProcessCollection $collection )
    {
        $this->_collection = $collection;

    }

    public function current ()
    {
        $return = parent::current();

        return new ClientProcessDecorator($return);

    }

}

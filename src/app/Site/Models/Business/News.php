<?php

namespace Site\Models\Business;

use Site\Models\DataAccess;

class News
{

    protected $_find;

    public function __construct ()
    {
        $this->_find = new \Site\Models\DataAccess\Find\News\News;

    }

    public function setUri ( $uri )
    {
        $this->_find->setUri($uri);

    }

    public function getEntity ()
    {
        $this->_find->prepare();

        try {
            $entity = $this->_find->getEntity();
        } catch (\Site\Models\DataAccess\Find\Exception\ContentNotFoundException $e) {
            throw new \Site\Models\Business\Exception\ContentNotFoundException($e->getMessage());
        }

        return new \Site\Models\DataAccess\Entity\Decorators\NewsDecorator($entity);

    }

}

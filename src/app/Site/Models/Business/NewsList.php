<?php

namespace Site\Models\Business;

use Site\Helpers\PaginatorSite;
use Site\Models\DataAccess\Collection\Decorators\NewsDecoratorCollection;

class NewsList
{

    protected $_find;
    protected $_curent_page;
    protected $_itens_per_page;
    protected $_paginator;
    protected $_total;
    protected $_offset;

    public function __construct ()
    {
        $this->_find = new \Site\Models\DataAccess\Find\News\NewsList;

    }

    public function getCollection ()
    {
        try {
            $this->setTotal();
            $this->setPaginator();
            $this->setOffset();
            $this->_find->setOffset($this->_offset);
            $this->_find->setLimit($this->_itens_per_page);
            $this->_find->prepare();
            $collection = $this->_find->getAll();
        } catch (\Site\Models\DataAccess\Find\Exception\ContentNotFoundException $e) {
            throw new \Site\Models\Business\Exception\ContentNotFoundException($e->getMessage());
        }
        return new NewsDecoratorCollection($collection);

    }

    public function setCurrentPage ( $current_page )
    {
        $this->_curent_page = $current_page;

    }

    public function setItensPerPage ( $itens_per_page )
    {
        $this->_itens_per_page = $itens_per_page;

    }

    protected function setPaginator ()
    {
        $this->_paginator = new PaginatorSite($this->_itens_per_page, $this->_curent_page);

    }

    protected function setOffset ()
    {
        $this->_offset = $this->_paginator->getOffset($this->getTotal());

    }

    public function getPaginator ()
    {
        return $this->_paginator;

    }

    public function setTotal ()
    {
        $this->_total = $this->_find->getTotal();

    }

    public function getTotal ()
    {
        return $this->_total;

    }

    public function getOffset ()
    {
        return $this->_offset + 1;

    }

    public function getItensPerPage ()
    {
        return $this->_itens_per_page;

    }

}

<?php

namespace Site\Models\DataAccess\Find\News;

use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Collection\NewsCollection;

class NewsList extends AbstractFind
{

    protected $_offset;

    public function __construct ()
    {
        parent::__construct();
        $this->selectAll();

    }

    public function selectAll ()
    {
        $this->_select = new Select2('news');
        $this->_select->addField('id_news');
        $this->_select->addField('title');
        $this->_select->addField('body');
        $this->_select->addField('inc_date');
        $this->_select->addField('head');
        $this->_select->addField('cover');
        $this->_select->addField('uri');
        $this->_select->order_by('date DESC');

        $this->_criteria = array(
            'is_del = ?' => 0,
            'is_active = ?' => 1
        );

    }

    public function getTotal ()
    {
        $this->_select->where(new Criteria($this->_criteria));
        return $this->_dao->select_count($this->_select);

    }

    public function prepare ()
    {
        $this->_select->where(new Criteria($this->_criteria));

    }

    public function setLimit ( $limit )
    {
        $this->_select->limit($limit, $this->_offset);

    }

    public function setOffset ( $offset )
    {
        $this->_offset = $offset;

    }

    public function getAll ()
    {
        $collection = $this->_dao->select_iterator($this->_select, new \Site\Models\DataAccess\Entity\News, new NewsCollection);
        return $collection;

    }

}

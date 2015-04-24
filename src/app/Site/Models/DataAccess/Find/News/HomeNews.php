<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\HomeNewsCollection;

class HomeNews extends AbstractDAOClient
{

    /**
     *
     * @return HomeNewsCollection
     */
    public function getAll ()
    {
        $select = new Select2('news');
        $select->addField('id_news');
        $select->addField('title');
        $select->addField('head');
        $select->addField('date');
        $select->addField('cover');
        $select->addField('uri');

        $select->where(new Criteria(array(
            'is_active = ?' => 1,
            'is_del = ?' => 0,
            'is_featured = ?' => 0
        )));

        $select->order_by('date DESC');
        $select->limit(3, 0);

        $collection = $this->_dao->select_iterator($select, new Entity\HomeNews, new HomeNewsCollection);
        return $collection;

    }

}

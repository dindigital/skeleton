<?php

namespace Site\Models\DataAccess\Find\News;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\FeaturedNewsCarouselCollection;

class FeaturedNewsCarousel extends AbstractDAOClient
{

    /**
     *
     * @return FeaturedNewsCarouselCollection
     */
    public function getAll ()
    {
        $select = new Select2('news');
        $select->addField('id_news');
        $select->addField('title');
        $select->addField('date');
        $select->addField('cover');
        $select->addField('uri');

        $select->where(new Criteria(array(
            'is_active = ?' => 1,
            'is_del = ?' => 0,
            'is_featured = ?' => 1,
        )));

        $select->order_by('sequence=0,sequence,date DESC');
        $select->limit(4, 0);
        $collection = $this->_dao->select_iterator($select, new Entity\FeaturedNewsCarousel, new FeaturedNewsCarouselCollection);

        return $collection;

    }

}

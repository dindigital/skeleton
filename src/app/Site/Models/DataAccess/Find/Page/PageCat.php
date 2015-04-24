<?php

namespace Site\Models\DataAccess\Find\Page;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\PageCollection;

class PageCat extends AbstractDAOClient
{

  public function getByIdPage ( $id_page )
  {

    $criteria = new Criteria(array(
        'page.id_page = ?' => $id_page
    ));

    $select = new Select2('page_cat');
    $select->addField('title');
    $select->addField('uri');

    $select->inner_join('page', 'id_page_cat', 'id_page_cat');

    $select->where($criteria);

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\Page, new PageCollection);

    return $result;

  }

}

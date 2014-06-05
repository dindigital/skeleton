<?php

namespace Site\Models\DataAccess;

use Site\Models\DataAccess\AbstractDataAccess;
use Din\DataAccessLayer\Select;
use Site\Models\Entities;

class PageCat extends AbstractDataAccess
{

  public function getNav ()
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1'
    );

    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('url');
    $select->addField('target');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,title');

    $result = $this->_dao->select($select, new Entities\PageCat);

    return $result;

  }

  public function getPageCat ( $uri )
  {

    $uri = "/$uri/";

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1',
        'a.uri = ?' => $uri
    );

    $select = new Select('page_cat');
    $select->addAllFields();
    $select->where($arrCriteria);

    $result = $this->_dao->select($select, new Entities\PageCat);

    return $result;

  }

  public function getPageSitemap ()
  {

    $criteria = array(
        'is_active = ?' => '1',
        'is_del = ?' => '0'
    );

    $select_cat = new Select('page_cat');
    $select_cat->addField('uri');
    $select_cat->addField('url');
    $select_cat->addField('inc_date', 'date');

    $select_page = new Select('page');
    $select_page->addField('uri');
    $select_page->addField('url');
    $select_page->addField('inc_date', 'date');

    $select_cat->where($criteria);

    $select_cat->union($select_page);

    $result = $this->_dao->select($select_cat, new Entities\PageCat);

    return $result;

  }

}

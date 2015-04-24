<?php

namespace Site\Models\DataAccess\Find\Sitemap\Areas;

use Site\Models\DataAccess\Find\Sitemap\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Din\DataAccessLayer\Select\Union;

class Page implements AreaInterface
{

  public function getSelect ()
  {
    $page_cat = new Select2('page_cat');
    $page_cat->addField('sequence');
    $page_cat->addField('uri');
    $page_cat->addFField('inc_date', 'lastmod');
    $page_cat->addFField('1', 'order1');

    $page_cat->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'url <> ?' => '#',
    )));

    $page = new Select2('page');
    $page->addField('sequence');
    $page->addField('uri');
    $page->addFField('inc_date', 'lastmod');
    $page->addFField('2', 'order1');

    $page->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'url <> ?' => '#',
    )));

    $page_ind = new Select2('page_ind');
    $page_ind->addField('sequence');
    $page_ind->addField('uri');
    $page_ind->addFField('inc_date', 'lastmod');
    $page_ind->addFField('3', 'order1');
    $page_ind->addFField('-1', 'sequence');

    $page_ind->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $select = new Union;
    $select->addSelect($page_cat);
    $select->addSelect($page);
    $select->addSelect($page_ind);

    $select->order_by('order1,sequence,lastmod DESC');

    return $select;

  }

}

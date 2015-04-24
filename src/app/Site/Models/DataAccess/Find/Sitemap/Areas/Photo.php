<?php

namespace Site\Models\DataAccess\Find\Sitemap\Areas;

use Site\Models\DataAccess\Find\Sitemap\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Photo implements AreaInterface
{

  public function getSelect ()
  {
    $select = new Select2('photo');
    $select->addField('uri');
    $select->addFField('IF (upd_date, upd_date, inc_date)', 'lastmod');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $select->order_by('lastmod DESC');

    return $select;

  }

}

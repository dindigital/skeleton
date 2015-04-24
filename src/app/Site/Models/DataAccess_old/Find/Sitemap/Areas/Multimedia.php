<?php

namespace Site\Models\DataAccess\Find\Sitemap\Areas;

use Site\Models\DataAccess\Find\Sitemap\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Din\DataAccessLayer\Select\Union;

class Multimedia implements AreaInterface
{

  public function getSelect ()
  {
    $audio = new Select2('audio');
    $audio->addField('sequence');
    $audio->addField('uri');
    $audio->addFField('inc_date', 'lastmod');

    $audio->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $video = new Select2('video');
    $video->addField('sequence');
    $video->addField('uri');
    $video->addFField('inc_date', 'lastmod');

    $video->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $photo = new Select2('photo');
    $photo->addField('sequence');
    $photo->addField('uri');
    $photo->addFField('inc_date', 'lastmod');
    $photo->addFField('-1', 'sequence');

    $photo->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
    )));

    $select = new Union;
    $select->addSelect($audio);
    $select->addSelect($video);
    $select->addSelect($photo);

    $select->order_by('sequence,lastmod DESC');

    return $select;

  }

}

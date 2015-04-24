<?php

namespace Site\Models\DataAccess\Find\Photo;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\PhotoCollection;

class Photo extends AbstractDAOClient
{

  /**
   *
   * @return NewsCollection
   */
  public function getByUri ( $uri )
  {
    $select = new Select2('photo');
    $select->addField('id_photo');
    $select->addField('uri');
    $select->addField('short_link');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('content');
    $select->addField('description');
    $select->addField('keywords');
    $select->addField('upd_date');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'uri = ?' => $uri,
    )));

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\PhotoGallery, new PhotoCollection);

    return $result;

  }

}

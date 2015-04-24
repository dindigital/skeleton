<?php

namespace Site\Models\DataAccess\Find\Photo;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\PhotoItemCollection;

class PhotoItem extends AbstractFind
{

  /**
   *
   * @return PhotoItemCollection
   */
  public function getByIdPhoto ( $id_photo )
  {

    $select = new Select2('photo_item');
    $select->addField('id_photo_item');
    $select->addField('file');
    $select->addField('label');
    $select->addField('credit');
    $select->addField('place');

    $select->where(new Criteria(array(
        'id_photo = ?' => $id_photo
    )));

    $select->order_by('sequence');
    if ( !is_null($this->_limit) ) {
      $select->limit($this->_limit, $this->_offset);
    }

    $result = $this->_dao->select_iterator($select, new Entity\PhotoItem, new PhotoItemCollection);

    return $result;

  }

  public function getItemByIdPhotoItem ( $id_photo_item )
  {
    $select = new Select2('photo_item');
    $select->addField('file');
    $select->addField('label');
    $select->addField('sequence');
    $select->addField('title', 'title', 'photo');
    $select->inner_join('photo', 'id_photo', 'id_photo');

    $select->where(new Criteria(array(
        'id_photo_item = ?' => $id_photo_item
    )));

    $result = $this->_dao->select($select);

    return $result;

  }

}

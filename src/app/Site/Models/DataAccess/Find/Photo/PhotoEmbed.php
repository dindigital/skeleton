<?php

namespace Site\Models\DataAccess\Find\Photo;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\PhotoCollection;

class PhotoEmbed extends AbstractDAOClient
{

  protected $_criteria;
  protected $_select;

  public function __construct ()
  {
    parent::__construct();

    $this->_criteria = array(
        'is_active = ?' => 1,
        'is_del = ?' => 0
    );
    $this->_select = new Select2('photo');
    $this->_select->addField('id_photo');
    $this->_select->addField('uri');
    $this->_select->addField('short_link');
    $this->_select->addField('title');
    $this->_select->addField('date');
    $this->_select->addField('cover');
    $this->_select->addField('content');
    $this->_select->addField('description');
    $this->_select->addField('keywords');
    $this->_select->addField('upd_date');

  }

  public function setIdPhoto ( $id_photo )
  {
    $this->_criteria['id_photo = ?'] = $id_photo;

  }

  /**
   *
   * @return PhotoCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    $result = $this->_dao->select_iterator($this->_select, new Entity\PhotoGallery, new PhotoCollection);

    return $result;

  }

}

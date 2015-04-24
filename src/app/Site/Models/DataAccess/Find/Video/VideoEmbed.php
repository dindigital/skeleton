<?php

namespace Site\Models\DataAccess\Find\Video;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\VideoCollection;

class VideoEmbed extends AbstractDAOClient
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
    $this->_select = new Select2('video');
    $this->_select->addField('id_video');
    $this->_select->addField('title');
    $this->_select->addField('file');
    $this->_select->addField('cover');
    $this->_select->addField('link_youtube');
    $this->_select->addField('link_vimeo');
    $this->_select->addField('id_youtube');

  }

  public function setIdVideo ( $id_video )
  {
    $this->_criteria['id_video = ?'] = $id_video;

  }

  /**
   *
   * @return AudioCollection
   */
  public function getCollection ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    $result = $this->_dao->select_iterator($this->_select, new Entity\VideoGallery, new VideoCollection);

    return $result;

  }

}

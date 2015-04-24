<?php

namespace Site\Models\DataAccess\Find\Video;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\VideoCollection;

class Video extends AbstractDAOClient
{

  /**
   *
   * @return NewsCollection
   */
  public function getByUri ( $uri )
  {
    $select = new Select2('video');
    $select->addField('id_video');
    $select->addField('uri');
    $select->addField('short_link');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('file');
    $select->addField('content');
    $select->addField('description');
    $select->addField('keywords');
    $select->addField('link_youtube');
    $select->addField('link_vimeo');
    $select->addField('id_youtube');
    $select->addField('upd_date');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'uri = ?' => $uri,
    )));

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\VideoGallery, new VideoCollection);

    return $result;

  }

  /**
   * Lista os últimos vídeos para aside
   * @param string $id Id de vídeo aberto, caso exista
   * @param type $limit Limite de vídeos exibidos
   * @return type
   */
  public function getLastVideos ( $id, $limit )
  {

    $criteria = array(
        'video.is_active = ?' => 1,
        'video.is_del = ?' => 0,
        'gallery_cat.is_active = ?' => 1,
        'gallery_cat.is_del = ?' => 0,
    );

    if ( !is_null($id) ) {
      $criteria['video.id_video <> ?'] = $id;
    }

    $select = new Select2('video');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('cover');

    $select->inner_join('gallery_cat', 'id_gallery_cat', 'id_gallery_cat');
    $select->addField('title', 'category', 'gallery_cat');

    $select->where(new Criteria($criteria));

    $select->order_by('date DESC');
    $select->limit($limit);

    $result = $this->_dao->select_iterator($select, new Entity\VideoGallery, new VideoCollection);

    return $result;

  }

}

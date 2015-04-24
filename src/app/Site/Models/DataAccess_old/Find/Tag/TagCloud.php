<?php

namespace Site\Models\DataAccess\Find\Tag;

use Site\Models\DataAccess\Find\AbstractFind;
use Site\Models\DataAccess\Entity;
use Site\Models\DataAccess\Collection\TagCollection;

class TagCloud extends AbstractFind
{

  public function __construct ()
  {
    parent::__construct();
    $this->_limit = 10;

  }

  /**
   *
   * @return TagCollection
   */
  public function getCollection ()
  {
    $SQL = '
        SELECT SUM(total) total, id_tag, title FROM (
                SELECT COUNT(tag.id_tag) total, r_action_tag.id_tag, tag.title FROM r_action_tag
                  INNER JOIN tag ON tag.id_tag=r_action_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY tag.id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_event_tag.id_tag, tag.title FROM r_event_tag
                  INNER JOIN tag ON tag.id_tag=r_event_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY tag.id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_audio_tag.id_tag, tag.title FROM r_audio_tag
                  INNER JOIN tag ON tag.id_tag=r_audio_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY tag.id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_news_tag.id_tag, tag.title FROM r_news_tag
                  INNER JOIN tag ON tag.id_tag=r_news_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY tag.id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_page_cat_tag.id_tag, tag.title FROM r_page_cat_tag
                  INNER JOIN tag ON tag.id_tag=r_page_cat_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_page_ind_tag.id_tag, tag.title FROM r_page_ind_tag
                  INNER JOIN tag ON tag.id_tag=r_page_ind_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_page_tag.id_tag, tag.title FROM r_page_tag
                  INNER JOIN tag ON tag.id_tag=r_page_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_photo_tag.id_tag, tag.title FROM r_photo_tag
                  INNER JOIN tag ON tag.id_tag=r_photo_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_secretary_manager_tag.id_tag, tag.title FROM r_secretary_manager_tag
                  INNER JOIN tag ON tag.id_tag=r_secretary_manager_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_standpoint_tag.id_tag, tag.title FROM r_standpoint_tag
                  INNER JOIN tag ON tag.id_tag=r_standpoint_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
                UNION ALL
                SELECT COUNT(tag.id_tag) total, r_video_tag.id_tag, tag.title FROM r_video_tag
                  INNER JOIN tag ON tag.id_tag=r_video_tag.id_tag WHERE is_active=1 AND is_del=0 GROUP BY id_tag
              ) a

              GROUP BY id_tag
              ORDER BY total DESC
        LIMIT ' . $this->_limit . '
    ';

    $select = new \Din\DataAccessLayer\Select\SelectFoo();
    $select->setSQL($SQL);

    $result = $this->_dao->select($select);

    $collection = $this->workWithNumbers($result);

    return $collection;

  }

  protected function workWithNumbers ( $result )
  {
    $collection = new TagCollection();
    $itens = array();

    if ( count($result) ) {
      $max = intval($result[0]['total']);

      shuffle($result);

      foreach ( $result as $row ) {
        $tag = new Entity\Tag;
        $tag->setField('size', $this->calcPercentual($max, $row['total']));
        $tag->setField('id_tag', $row['id_tag']);
        $tag->setField('title', $row['title']);

        $itens[] = $tag;
      }
    }

    $collection->setItens($itens);

    return $collection;

  }

  protected function calcPercentual ( $max, $current )
  {
    return intval($current * 100 / $max);

  }

}

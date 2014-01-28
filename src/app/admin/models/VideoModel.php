<?php

namespace src\app\admin\models;

use src\app\admin\validators\VideoValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;

/**
 *
 * @package app.models
 */
class VideoModel extends BaseModelAdm
{

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    $select = new Select('video');
    $select->addField('id_video');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator();
    $id = $validator->setId($this);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDescription($info['description']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setDefaultLink('video', $info['title'], $id);
    $validator->setShortenerLink();
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDescription($info['description']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setDefaultLink('video', $info['title'], $id);
    $validator->setShortenerLink();
    $validator->throwException();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getNew ()
  {
    return array(
        'id_video' => null,
        'active' => null,
        'title' => null,
        'date' => date('Y-m-d'),
        'description' => null,
        'link_youtube' => null,
        'link_vimeo' => null,
        'inc_date' => null,
        'del_date' => null,
        'is_del' => null,
        'link' => null,
        'short_link' => null,
    );
  }

}

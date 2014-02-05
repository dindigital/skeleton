<?php

namespace src\app\admin\models;

use src\app\admin\validators\VideoValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\TagModel;

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
    $select->addField('uri');
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
    $this->setId($validator->setId($this));
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDescription($info['description']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'video');
    $validator->setShortenerLink();
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    $tagmodel = new TagModel();
    $tagmodel->insertAjax($this->getId(), $info['tags']);
  }

  public function update ( $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDescription($info['description']);
    $validator->setLinkYouTube($info['link_youtube']);
    $validator->setLinkVimeo($info['link_vimeo']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'video', $info['uri']);
    $validator->setShortenerLink();
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_video = ?' => $this->getId()));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    $tagmodel = new TagModel();
    $tagmodel->insertAjax($this->getId(), $info['tags']);
  }

}

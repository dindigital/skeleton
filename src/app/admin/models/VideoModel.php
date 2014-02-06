<?php

namespace src\app\admin\models;

use src\app\admin\validators\VideoValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\RelationshipModel;

/**
 *
 * @package app.models
 */
class VideoModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('video');
  }

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

  public function getNew ()
  {
    $row = parent::getNew();
    $row['date'] = date('Y-m-d');

    return $row;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
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

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);

    $this->relationship('tag', $info['tag']);
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
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
    $this->_dao->update($this->_table, array('id_video = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);

    $this->relationship('tag', $info['tag']);
  }

  private function relationship ( $tbl, $array )
  {
//    $relationshipModel = new RelationshipModel();
//    $relationshipModel->setCurrentSection('video');
//    $relationshipModel->setRelationshipSection($tbl);
//    $relationshipModel->insert($this->getId(), $array);
  }

}

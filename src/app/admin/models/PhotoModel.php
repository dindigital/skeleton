<?php

namespace src\app\admin\models;

use src\app\admin\validators\PhotoValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\GalleryModel;

/**
 *
 * @package app.models
 */
class PhotoModel extends BaseModelAdm
{

  private $_gallery;

  public function __construct ()
  {
    parent::__construct();
    $this->_gallery = new GalleryModel('photo', 'photo_item');
    $this->setTable('photo');
  }

  public function getById ( $id = null )
  {
    $row = parent::getById($id);
    $row['gallery'] = $this->_gallery->getList(array('id_photo = ?' => $this->getId()));

    return $row;
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . @$arrFilters['title'] . '%'
    );

    $select = new Select('photo');
    $select->addField('id_photo');
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
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'photo');
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);
    $this->_gallery->saveGalery($info['gallery_uploader'], $this->getId());
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'photo', $info['uri']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_photo = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);

    $this->_gallery->saveGalery($info['gallery_uploader'], $this->getId(), $info['sequence'], $info['label'], $info['credit']);
  }

  public function getNew ()
  {
    $arr_return = parent::getNew();
    $arr_return['date'] = date('Y-m-d');
    $arr_return['gallery'] = array();

    return $arr_return;
  }

}

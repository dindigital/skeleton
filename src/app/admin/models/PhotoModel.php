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
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());
    $this->_gallery->saveGalery($info['gallery_uploader'], $this->getId());
  }

  public function update ( $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_photo = ?' => $this->getId()));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

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

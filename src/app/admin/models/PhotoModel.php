<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\GalleryModel;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Gallery;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

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

  /* public function getById ( $id = null )
    {
    $row = parent::getById($id);
    $row['gallery'] = $this->_gallery->getList(array('id_photo = ?' => $this->getId()));

    return $row;
    }

    public function getNew ()
    {
    $arr_return = parent::getNew();
    $arr_return['date'] = date('Y-m-d');
    $arr_return['gallery'] = array();

    return $arr_return;
    }
   */

  public function formatTable ( $table )
  {

    if ( isset($table['id_photo']) && $table['id_photo'] ) {
      $table['gallery'] = $this->_gallery->getList(array('id_photo = ?' => $table['id_photo']));
    } else {
      $table['date'] = date('Y-m-d');
      $table['gallery'] = array();
    }

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $uploader = Form::Upload('gallery_uploader', '', 'image', true, false);
    $table['gallery'] = $uploader . Gallery::get($table['gallery'], 'gallery');
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('photo');
    $select->addField('id_photo');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'photo');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');
    $validator->throwException();

    $this->dao_insert();

    $this->_gallery->saveGalery($input['gallery_uploader'], $this->getId());
  }

  public function update ( $input )
  {

    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'photo', $input['uri']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');
    $validator->throwException();

    $this->dao_update();

    $this->_gallery->saveGalery($input['gallery_uploader'], $this->getId(), $input['sequence'], $input['label'], $input['credit']);
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

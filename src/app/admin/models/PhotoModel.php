<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Gallery;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;
use src\app\admin\models\essential\GaleryModel;

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
    $this->_gallery = new GaleryModel(array(
        'photo' => 'photo',
        'id_photo' => 'id_photo',
        'photo_item' => 'photo_item',
        'id_photo_item' => 'id_photo_item',
    ));
    $this->setEntity('photo');
  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['gallery'] = array();
      $table['uri'] = null;
    }

    if ( $table['id_photo'] && !$exclude_fields ) {
      $table['gallery'] = $this->_gallery->getList(array('id_photo = ?' => $table['id_photo']));
    } else {
      $table['date'] = date('Y-m-d');
      $table['gallery'] = array();
    }

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $uploader = Form::Upload('gallery_uploader', '', 'image', true, false);
    $table['gallery_uploader'] = $uploader . Gallery::get($table['gallery'], 'gallery');
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
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->date()->validate('date', 'Data');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_photo');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId(), 'fotos')->filter('uri');
    //
    $this->dao_insert();

    $this->_gallery->saveGalery($input['gallery_uploader'], $this->getId());
  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->date()->validate('date', 'Data');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId(), 'fotos')->filter('uri');
    //
    $this->dao_update();

    $this->_gallery->saveGalery($input['gallery_uploader'], $this->getId(), $input['sequence'], $input['label'], $input['credit']);
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

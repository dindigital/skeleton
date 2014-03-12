<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\RelationshipModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

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

  public function formatTable ( $table )
  {

    if ( is_null($table['date']) ) {
      $table['date'] = date('Y-m-d');
    }

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('video');
    $select->addField('id_video');
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
      $result[$i]['title'] = Html::scape($row['title']);
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'video');
    $this->setShortenerLink();
    $this->_table->link_youtube = $input['link_youtube'];
    $this->_table->link_vimeo = $input['link_vimeo'];

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');
    $validator->setRequiredString('description', 'Descrição');
    $validator->throwException();

    $this->dao_insert();

    $this->relationship('tag', $input['tag']);
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'video', $input['uri']);
    $this->setShortenerLink();
    $this->_table->link_youtube = $input['link_youtube'];
    $this->_table->link_vimeo = $input['link_vimeo'];

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');
    $validator->setRequiredString('description', 'Título');
    $validator->throwException();

    $this->dao_update();

    $this->relationship('tag', $input['tag']);
  }

  private function relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('video');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->insert($this->getId(), $array);
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    return $this->_filters;
  }

}

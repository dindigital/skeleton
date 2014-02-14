<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
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

}

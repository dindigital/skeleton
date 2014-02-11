<?php

namespace src\app\admin\models;

use src\app\admin\validators\PageCatValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\SequenceModel;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class PageCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('page_cat');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq = new SequenceModel($this);
    $result = $seq->setListArray($result, $arrCriteria);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $this->setIntval('active', $info['active']);
    $this->setTimestamp('inc_date');
    $this->setDefaultUri($info['title'], 'page');
    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);

    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $seq = new SequenceModel($this);
    $seq->setSequence();

    $mf->move();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);
  }

  public function update ( $info )
  {
    $this->setIntval('active', $info['active']);
    $this->setDefaultUri($info['title'], 'page', $info['uri']);
    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_page_cat = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);
  }

  public function getListArray ()
  {
    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_page_cat']] = $row['title'];
    }

    return $arrOptions;
  }

}

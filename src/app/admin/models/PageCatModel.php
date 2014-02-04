<?php

namespace src\app\admin\models;

use src\app\admin\validators\PageCatValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class PageCatModel extends BaseModelAdm
{

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
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);
    $result = Sequence::setListArray($this, $result, $arrCriteria);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator;
    $this->setId($validator->setId($this));
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->setIncDate();
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    Sequence::setSequence($this, $validator);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());
  }

  public function update ( $info )
  {
    $validator = new validator;
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_page_cat = ?' => $this->getId()));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);
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

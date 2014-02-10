<?php

namespace src\app\admin\models;

use src\app\admin\validators\NewsCatValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class NewsCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('news_cat');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );
    if ( isset($arrFilters['is_home']) && $arrFilters['is_home'] == '1' ) {
      $arrCriteria['is_home = ?'] = '1';
    } elseif ( isset($arrFilters['is_home']) && $arrFilters['is_home'] == '2' ) {
      $arrCriteria['is_home = ?'] = '0';
    }

    $select = new Select('news_cat');
    $select->addField('id_news_cat');
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
    $result = Sequence::setListArray($this, $result, $arrCriteria);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $this->setIntval('active', $info['active']);
    $this->setIntval('is_home', $info['is_home']);
    $this->setTimestamp('inc_date');
    $this->setDefaultUri($info['title'], 'news');

    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);

    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    Sequence::setSequence($this);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);
  }

  public function update ( $info )
  {
    $this->setIntval('active', $info['active']);
    $this->setIntval('is_home', $info['is_home']);
    $this->setDefaultUri($info['title'], 'news', $info['uri']);

    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);

    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_news_cat = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);
  }

  public function getListArray ()
  {
    $select = new Select('news_cat');
    $select->addField('id_news_cat');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_news_cat']] = $row['title'];
    }

    return $arrOptions;
  }

}

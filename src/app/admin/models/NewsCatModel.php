<?php

namespace src\app\admin\models;

use src\app\admin\validators\NewsCatValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;

/**
 *
 * @package app.models
 */
class NewsCatModel extends BaseModelAdm
{

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
    $validator = new validator();
    $id = $validator->setId($this);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setIsHome($info['is_home']);
    $validator->setIncDate();

    Sequence::setSequence($this, $validator);
    $validator->throwException();

    $validator->setFile('cover', $info['cover'], $id);

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setIsHome($info['is_home']);
    $validator->throwException();

    $validator->setFile('cover', $info['cover'], $id);

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_news_cat = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getNew ()
  {
    return array(
        'id_news_cat' => null,
        'active' => null,
        'title' => null,
        'inc_date' => null,
        'del_date' => null,
        'is_del' => null,
        'cover' => null,
        'sequence' => null,
        'is_home' => null,
    );
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

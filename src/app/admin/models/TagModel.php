<?php

namespace src\app\admin\models;

use src\app\admin\validators\TagValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use \src\app\admin\helpers\Listbox;

/**
 *
 * @package app.models
 */
class TagModel extends BaseModelAdm
{

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    $select = new Select('tag');
    $select->addField('id_tag');
    $select->addField('active');
    $select->addField('title');
    $select->where($arrCriteria);
    $select->order_by('title');

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
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());
  }

  public function update ( $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_tag = ?' => $this->getId()));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);
  }

  public function getAjax ( $q )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $q . '%'
    );

    $select = new Select('tag');
    $select->addField('id_tag', 'id');
    $select->addField('title', 'text');
    $select->where($arrCriteria);
    $select->order_by('title');

    $result = $this->_dao->select($select);

    $result = json_encode($result);

    return $result;
  }

  public function insertAjax ( $id_video, $tags )
  {
    $arrTags = explode(',', $tags);
    $arrIds = array();
    foreach ( $arrTags as $row ) {
      $validator = new validator();
      $this->setId($validator->setId($this));
      $validator->setActive(1);
      $validator->setIncDate();
      if ( $validator->setTag($this->_dao, $row) ) {
        $id_tag = $this->_dao->insert($validator->getTable());
        $this->log('C', $row, $validator->getTable());
        $arrIds[] = $id_tag;
      } else {
        $arrIds[] = $row;
      }
    }

    var_dump($arrIds);

    $listbox = new Listbox($this->_dao);
    $listbox->insertRelationship('r_video_tag', 'id_video', $id_video, 'id_tag', $arrIds);
  }

}

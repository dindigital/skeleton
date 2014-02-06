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

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('tag');
  }

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
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_tag = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);
  }

}

<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;

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

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('title', 'Título');
    $validator->throwException();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);

    $validator = new validator($this->_table);
    $validator->setId($this->getId());
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('title', 'Título', $this->getIdName());
    $validator->throwException();

    $this->dao_update();
  }

  public function short_insert ( $title )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');

    $validator = new validator($this->_table);
    $validator->setInput(array('title' => $title));
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('title', 'Título');
    $validator->throwException();

    $this->dao_insert();
  }

}

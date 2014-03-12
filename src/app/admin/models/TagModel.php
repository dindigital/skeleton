<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\String\Html;

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

  public function formatTable ( $table )
  {
    $table['title'] = Html::scape($table['title']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('tag');
    $select->addField('id_tag');
    $select->addField('active');
    $select->addField('title');
    $select->where($arrCriteria);
    $select->order_by('title');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
    }

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

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

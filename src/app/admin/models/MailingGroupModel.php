<?php

namespace src\app\admin\models;

use src\app\admin\validators\MailingGroupValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;

/**
 *
 * @package app.models
 */
class MailingGroupModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('mailing_group');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $arrFilters['name'] . '%'
    );

    $select = new Select('mailing_group');
    $select->addField('id_mailing_group');
    $select->addField('name');
    $select->where($arrCriteria);
    $select->order_by('name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setName($info['name']);
    $validator->throwException();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['name'], $this->_table);
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setName($info['name']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_mailing_group = ?' => $this->getId()));
    $this->log('U', $info['name'], $this->_table, $tableHistory);
  }

  public function short_insert ( $name )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setName($name);
    $validator->throwException();

    $this->_dao->insert($this->_table);
    $this->log('C', $name, $this->_table);
  }

}

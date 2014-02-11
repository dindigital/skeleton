<?php

namespace src\app\admin\models;

use src\app\admin\validators\MailingValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;

/**
 *
 * @package app.models
 */
class MailingModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('mailing');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $arrFilters['name'] . '%',
        'email LIKE ?' => '%' . $arrFilters['email'] . '%',
    );

    $select = new Select('mailing');
    $select->addField('id_mailing');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('email');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table, $this->_dao);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->throwException();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['name'], $this->_table);
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table, $this->_dao);
    $validator->setName($info['name']);
    $validator->setEmail($info['email'], $this->getId());
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_mailing = ?' => $this->getId()));
    $this->log('U', $info['name'], $this->_table, $tableHistory);
  }

}

<?php

namespace src\app\admin\models;

use src\app\admin\helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\AdminValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class AdminModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('admin');
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $this->setIntval('active', $info['active']);
    $this->setTimestamp('inc_date');

    $validator = new validator($this->_table);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password']);
    $validator->setPermission($info['permission']);
    $mf = new MoveFiles;
    $validator->setFile('avatar', $info['avatar'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['name'], $this->_table);
  }

  public function update ( $info )
  {
    $this->setIntval('active', $info['active']);

    $validator = new validator($this->_table);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password'], false);
    $validator->setPermission($info['permission']);
    $mf = new MoveFiles;
    $validator->setFile('avatar', $info['avatar'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById($this->getId());
    $this->_dao->update($this->_table, array('id_admin = ?' => $this->getId()));
    $this->log('U', $info['name'], $this->_table, $tableHistory);
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $arrFilters['name'] . '%',
        'email LIKE ?' => '%' . $arrFilters['email'] . '%',
        'email <> ?' => 'suporte@dindigital.com'
    );

    $select = new Select('admin');
    $select->addField('id_admin');
    $select->addField('active');
    $select->addField('name');
    $select->addField('email');
    $select->addField('inc_date');
    $select->where($arrCriteria);
    $select->order_by('name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

}

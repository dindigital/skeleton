<?php

namespace src\app\admin\models;

use src\app\admin\helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\AdminValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;

/**
 *
 * @package app.models
 */
class AdminModel extends BaseModelAdm
{

  public function insert ( $info )
  {
    $validator = new validator;
    $validator->setActive($info['active']);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password']);
    $validator->setPermission($info['permission']);
    $validator->setIncDate();
    $id = $validator->setId($this);
    $validator->throwException();

    $validator->setFile('avatar', $info['avatar'], $id);

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['name'], $validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator;
    $validator->setActive($info['active']);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password'], false);
    $validator->setPermission($info['permission']);
    $validator->throwException();

    $validator->setFile('avatar', $info['avatar'], $id);

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_admin = ?' => $id));
    $this->log('U', $info['name'], $validator->getTable(), $tableHistory);
  }

  public function getNew ()
  {
    return array(
        'id_admin' => null,
        'active' => null,
        'name' => null,
        'email' => null,
        'password' => null,
        'avatar' => null,
        'inc_date' => null,
        'password_change_date' => null,
        'permission' => null,
    );
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

  public function delete ( $id )
  {
    $this->delete_permanent($id);
  }

}

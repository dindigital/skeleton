<?php

namespace src\app\admin\models;

use src\app\admin\helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\BaseValidator as validator;
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

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setIntval('active', $input['active']);
    $this->setTimestamp('inc_date');
    $this->_table->permission = json_encode($input['permission']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setId($this->getId());
    $validator->setRequiredString('name', 'Nome');
    $validator->setEmail('email', 'E-mail');
    $validator->setUniqueValue('email', 'E-mail');
    $validator->setPassword('password', 'Senha', true);

    $mf = new MoveFiles;
    $validator->setFile('avatar', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->_table->permission = json_encode($input['permission']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setId($this->getId());
    $validator->setRequiredString('name', 'Nome');
    $validator->setUniqueValue('email', 'E-mail', $this->getIdName());
    $validator->setEmail('email', 'E-mail');
    $validator->setPassword('password', 'Senha', false);

    $mf = new MoveFiles;
    $validator->setFile('avatar', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_update();
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

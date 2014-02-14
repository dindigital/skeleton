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

  public function insert ( $input )
  {
    $this->setNewId();

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('name', 'Nome');
    $validator->throwException();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('name', 'Nome', $this->getIdName());
    $validator->throwException();

    $this->dao_update();
  }

  public function short_insert ( $name )
  {
    $this->setNewId();

    $validator = new validator($this->_table);
    $validator->setInput(array('name' => $name));
    $validator->setDao($this->_dao);
    $validator->setUniqueValue('name', 'Nome');
    $validator->throwException();

    $this->dao_insert();
  }

  public function getListArray ()
  {
    $select = new Select('mailing_group');
    $select->addField('id_mailing_group');
    $select->addField('name');

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_mailing_group']] = $row['name'];
    }

    return $arrOptions;
  }

}

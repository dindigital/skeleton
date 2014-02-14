<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\RelationshipModel;

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

    if ( $arrFilters['mailing_group'] != '' && $arrFilters['mailing_group'] != '0' ) {
      $arrCriteria['id_mailing_group = ?'] = $arrFilters['mailing_group'];
    }

    $select = new Select('mailing');
    $select->addField('id_mailing');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('email');
    $select->group_by('a.id_mailing');

    $select->inner_join('id_mailing', Select::construct('r_mailing_mailing_group'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $input, $log = true )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setRequiredString('name', 'Nome');
    $validator->setUniqueValue('email', 'E-mail');
    $validator->throwException();

    $this->dao_insert(false);

    $this->save_relationship('mailing_group', $input['mailing_group']);
  }

  public function update ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setRequiredString('name', 'Nome');
    $validator->setUniqueValue('email', 'E-mail', $this->getIdName());
    $validator->throwException();

    $this->dao_update();

    $this->save_relationship('mailing_group', $input['mailing_group']);
  }

  private function save_relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('mailing');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->insert($this->getId(), $array);
  }

}

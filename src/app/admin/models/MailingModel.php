<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\RelationshipModel;
use Din\Filters\String\Html;
use src\app\admin\helpers\Form;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\DBValidator;
use Din\Exception\JsonException;
use src\app\admin\helpers\TableFilter;

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

  public function formatTable ( $table )
  {
    $table['name'] = Html::scape($table['name']);
    $table['email'] = Html::scape($table['email']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $this->_filters['name'] . '%',
        'email LIKE ?' => '%' . $this->_filters['email'] . '%',
    );

    if ( $this->_filters['mailing_group'] != '' && $this->_filters['mailing_group'] != '0' ) {
      $arrCriteria['id_mailing_group = ?'] = $this->_filters['mailing_group'];
    }

    $select = new Select('mailing');
    $select->addField('id_mailing');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('email');
    $select->group_by('a.id_mailing');

    $select->inner_join('id_mailing', Select::construct('r_mailing_mailing_group'));

    $this->_itens_per_page = 50;
    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['name'] = Html::scape($row['name']);
    }

    return $result;
  }

  public function insert ( $input, $log = true )
  {
    $input['active'] = '1';

    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    $str_validator->validateRequiredEmail('email', 'E-mail');
    $str_validator->validateRequiredString('mailing_group', 'Grupo');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'mailing');
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_mailing');
    $filter->setTimestamp('inc_date');
    $filter->setIntval('active');
    $filter->setString('name');
    $filter->setString('email');

    $this->dao_insert();

    $this->save_relationship('mailing_group', $input['mailing_group']);
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    $str_validator->validateRequiredEmail('email', 'E-mail');
    $str_validator->validateRequiredString('mailing_group', 'Grupo');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'mailing');
    $db_validator->setId('id_mailing', $this->getId());
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('name');
    $filter->setString('email');

    $this->dao_update();

    $this->save_relationship('mailing_group', $input['mailing_group']);
  }

  public function formatFilters ()
  {
    $mailingGroupModel = new MailingGroupModel;
    $result = $mailingGroupModel->getListArray();

    $this->_filters['name'] = Html::scape($this->_filters['name']);
    $this->_filters['email'] = Html::scape($this->_filters['email']);
    $this->_filters['mailing_group'] = Form::Dropdown('mailing_group', $result, $this->_filters['mailing_group'], 'Filtro por Grupo');

    return $this->_filters;
  }

  private function save_relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('mailing');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->insert($this->getId(), $array);
  }

}

<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\DBValidator;
use Din\Exception\JsonException;
use src\app\admin\filters\TableFilter;

/**
 *
 * @package app.models
 */
class CustomerModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('customer');
  }

  public function formatTable ( $table, $excluded_fields = false )
  {
    if ( $excluded_fields ) {
      //
    }

    $table['business_name'] = Html::scape($table['business_name']);
    $table['name'] = Html::scape($table['name']);
    $table['document'] = Html::scape($table['document']);
    $table['email'] = Html::scape($table['email']);
    $table['address_postcode'] = Html::scape($table['address_postcode']);
    $table['address_street'] = Html::scape($table['address_street']);
    $table['address_area'] = Html::scape($table['address_area']);
    $table['address_number'] = Html::scape($table['address_number']);
    $table['address_complement'] = Html::scape($table['address_complement']);
    $table['address_state'] = Html::scape($table['address_state']);
    $table['address_city'] = Html::scape($table['address_city']);
    $table['phone_ddd'] = Html::scape($table['phone_ddd']);
    $table['phone_number'] = Html::scape($table['phone_number']);

    return $table;
  }

  public function getList ()
  {
    $arrCriteria = array(
        'a.name LIKE ?' => '%' . $this->_filters['name'] . '%',
        'a.email LIKE ?' => '%' . $this->_filters['email'] . '%'
    );

    $select = new Select('customer');
    $select->addField('id_customer');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('a.name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', "Título");
    $str_validator->validateRequiredString('document', "Documento");
    $str_validator->validateRequiredEmail('email', "email");
    $str_validator->validateRequiredString('address_postcode', "CEP");
    $str_validator->validateRequiredString('address_street', "Rua");
    $str_validator->validateRequiredString('address_number', "Número");
    $str_validator->validateRequiredString('address_state', "Estado");
    $str_validator->validateRequiredString('address_city', "Cidade");
    $str_validator->validateLenghtString('phone_ddd', "DDD", 2);
    $str_validator->validateLenghtString('phone_number', "Telefone", 8, 9);
    //
    $db_validator = new DBValidator($input, $this->_dao, 'customer');
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_customer');
    $filter->setTimestamp('inc_date');
    $filter->setString('name');
    $filter->setString('document');
    $filter->setString('email');
    $filter->setString('address_postcode');
    $filter->setString('address_street');
    $filter->setString('address_number');
    $filter->setString('address_state');
    $filter->setString('address_city');
    $filter->setString('phone_ddd');
    $filter->setString('phone_number');
    $filter->setString('address_complement');
    $filter->setString('business_name');

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', "Título");
    $str_validator->validateRequiredString('document', "Documento");
    $str_validator->validateRequiredEmail('email', "email");
    $str_validator->validateRequiredString('address_postcode', "CEP");
    $str_validator->validateRequiredString('address_street', "Rua");
    $str_validator->validateRequiredString('address_number', "Número");
    $str_validator->validateRequiredString('address_state', "Estado");
    $str_validator->validateRequiredString('address_city', "Cidade");
    $str_validator->validateLenghtString('phone_ddd', "DDD", 2);
    $str_validator->validateLenghtString('phone_number', "Telefone", 8, 9);
    //
    $db_validator = new DBValidator($input, $this->_dao, 'customer');
    $db_validator->setId('id_customer', $this->getId());
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('name');
    $filter->setString('document');
    $filter->setString('email');
    $filter->setString('address_postcode');
    $filter->setString('address_street');
    $filter->setString('address_number');
    $filter->setString('address_state');
    $filter->setString('address_city');
    $filter->setString('phone_ddd');
    $filter->setString('phone_number');
    $filter->setString('address_complement');
    $filter->setString('business_name');

    $this->dao_update();
  }

  public function formatFilters ()
  {
    $this->_filters['name'] = Html::scape($this->_filters['name']);
    $this->_filters['email'] = Html::scape($this->_filters['email']);

    return $this->_filters;
  }

}

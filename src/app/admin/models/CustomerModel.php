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
class CustomerModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('customer');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'a.name LIKE ?' => '%' . $arrFilters['name'] . '%',
        'a.email LIKE ?' => '%' . $arrFilters['email'] . '%'
    );

    $select = new Select('customer');
    $select->addField('id_customer');
    $select->addField('name');
    $select->addField('email');
    $select->where($arrCriteria);
    $select->order_by('a.name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->_table->business_name = $input['business_name'];
    $this->_table->address_complement = $input['address_complement'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setRequiredString('name', 'Nome');
    $validator->setRequiredString('document', 'Documento (CPF / CNPJ)');
    $validator->setEmail('email', 'E-mail');
    $validator->setRequiredString('address_postcode', 'CEP');
    $validator->setRequiredString('address_street', 'Endereço');
    $validator->setRequiredString('address_area', 'Bairro');
    $validator->setRequiredString('address_number', 'Número');
    $validator->setRequiredString('address_state', 'Estado');
    $validator->setRequiredString('address_city', 'Cidade');
    $validator->setRequiredString('phone_ddd', 'DDD');
    $validator->setRequiredString('phone_number', 'Telefone');
    $validator->throwException();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $this->_table->business_name = $input['business_name'];
    $this->_table->address_complement = $input['address_complement'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setRequiredString('name', 'Nome');
    $validator->setRequiredString('document', 'Documento (CPF / CNPJ)');
    $validator->setEmail('email', 'E-mail');
    $validator->setRequiredString('address_postcode', 'CEP');
    $validator->setRequiredString('address_street', 'Endereço');
    $validator->setRequiredString('address_area', 'Bairro');
    $validator->setRequiredString('address_number', 'Número');
    $validator->setRequiredString('address_state', 'Estado');
    $validator->setRequiredString('address_city', 'Cidade');
    $validator->setRequiredString('phone_ddd', 'DDD');
    $validator->setRequiredString('phone_number', 'Telefone');
    $validator->throwException();

    $this->dao_update();
  }

}

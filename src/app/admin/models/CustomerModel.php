<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\String\Html;

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

  public function formatTable ( $table )
  {
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
    $validator->setLenghtString('address_postcode', 'CEP', 9);
    $validator->setRequiredString('address_street', 'Endereço');
    $validator->setRequiredString('address_area', 'Bairro');
    $validator->setRequiredString('address_number', 'Número');
    $validator->setLenghtString('address_state', 'Estado', 2);
    $validator->setRequiredString('address_city', 'Cidade');
    $validator->setLenghtString('phone_ddd', 'DDD', 2, 2);
    $validator->setMinMaxString('phone_number', 'Telefone', 8, 9);
    $validator->throwException();

    $this->dao_update();
  }

  public function formatFilters ()
  {
    $this->_filters['name'] = Html::scape($this->_filters['name']);
    $this->_filters['email'] = Html::scape($this->_filters['email']);

    return $this->_filters;
  }

}

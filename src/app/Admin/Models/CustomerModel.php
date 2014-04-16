<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\Filters\String\Html;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;

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

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
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
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->string()->validate('document', 'Documento');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->stringLenght(9)->validate('address_postcode', 'CEP');
    $v->string()->validate('address_street', 'Rua');
    $v->string()->validate('address_number', 'Número');
    $v->string()->validate('address_state', 'Estado');
    $v->string()->validate('address_city', 'Cidade');
    $v->stringLenght(2)->validate('phone_ddd', 'DDD');
    $v->stringLenght(8, 9)->validate('phone_number', 'Telefone');
    $v->dbUnique($this->_dao, 'customer')->validate('email', 'E-mail');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_customer');
    $f->timestamp()->filter('inc_date');
    $f->string()->filter('name');
    $f->string()->filter('document');
    $f->string()->filter('email');
    $f->string()->filter('address_postcode');
    $f->string()->filter('address_street');
    $f->string()->filter('address_number');
    $f->string()->filter('address_state');
    $f->string()->filter('address_city');
    $f->string()->filter('phone_ddd');
    $f->string()->filter('phone_number');
    $f->string()->filter('address_complement');
    $f->string()->filter('business_name');

    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->string()->validate('document', 'Documento');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->stringLenght(9)->validate('address_postcode', 'CEP');
    $v->string()->validate('address_street', 'Rua');
    $v->string()->validate('address_number', 'Número');
    $v->string()->validate('address_state', 'Estado');
    $v->string()->validate('address_city', 'Cidade');
    $v->stringLenght(2)->validate('phone_ddd', 'DDD');
    $v->stringLenght(8, 9)->validate('phone_number', 'Telefone');
    $v->dbUnique($this->_dao, 'customer', 'id_customer', $this->getId())->validate('email', 'E-mail');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('name');
    $f->string()->filter('document');
    $f->string()->filter('email');
    $f->string()->filter('address_postcode');
    $f->string()->filter('address_street');
    $f->string()->filter('address_number');
    $f->string()->filter('address_state');
    $f->string()->filter('address_city');
    $f->string()->filter('phone_ddd');
    $f->string()->filter('phone_number');
    $f->string()->filter('address_complement');
    $f->string()->filter('business_name');

    $this->dao_update();

  }

  public function formatFilters ()
  {
    $this->_filters['name'] = Html::scape($this->_filters['name']);
    $this->_filters['email'] = Html::scape($this->_filters['email']);

    return $this->_filters;

  }

}

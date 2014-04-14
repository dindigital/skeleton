<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Helpers\PaginatorAdmin;
use Admin\Models\Essential\RelationshipModel;
use Din\Filters\String\Html;
use Admin\Helpers\Form;
use Admin\CustomFilter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class MailingModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('mailing');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      //
    }

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

  public function insert ( $input )
  {
    $input['active'] = '1';

    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->string()->validate('mailing_group', 'Grupo');
    $v->dbUnique($this->_dao, 'mailing')->validate('email', 'E-mail');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_mailing');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('name');
    $f->string()->filter('email');

    $this->dao_insert();

    $this->save_relationship('mailing_group', $input['mailing_group']);

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->string()->validate('mailing_group', 'Grupo');
    $v->dbUnique($this->_dao, 'mailing', 'id_mailing', $this->getId())->validate('email', 'E-mail');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('name');
    $f->string()->filter('email');

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

<?php

namespace Admin\Models;

use Admin\Helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use Admin\Models\Essential\BaseModelAdm;
use Admin\Helpers\MoveFiles;
use Admin\Models\Essential\PermissionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use Admin\Helpers\Form;
use Din\InputValidator\InputValidator;
use Din\TableFilter\TableFilter;

/**
 *
 * @package app.models
 */
class AdminModel extends BaseModelAdm
{

  public static $_master_id = 'b9211c2ba990c3efab12df5e71e4a359';

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('admin');

  }

  protected function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['avatar'] = null;
    }

    $table['name'] = Html::scape($table['name']);
    $table['avatar_uploader'] = Form::Upload('avatar', $table['avatar'], 'image');

    $permission = new PermissionModel;
    $permission_listbox = $permission->getArrayList();
    $table['permission'] = Form::Listbox('permission', $permission_listbox, json_decode($table['permission']));

    return $table;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->string()->validate('password', 'Senha');
    $v->dbUnique($this->_dao, 'admin')->validate('email', 'E-mail');
    $has_avatar = $v->upload()->validate('avatar', 'Avatar');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_admin');
    $f->intval()->filter('active');
    $f->timestamp()->filter('inc_date');
    $f->string()->filter('name');
    $f->string()->filter('email');
    $f->crypted()->filter('password');
    $f->json()->filter('permission');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/admin/{$this->getId()}/avatar", $has_avatar
            , $mf)->filter('avatar');
    //
    $mf->move();
    //
    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->stringEmail()->validate('email', 'E-mail');
    $v->dbUnique($this->_dao, 'admin', 'id_admin', $this->getId())->validate('email', 'E-mail');
    $has_avatar = $v->upload()->validate('avatar', 'Avatar');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('name');
    $f->string()->filter('email');
    $f->crypted()->filter('password');
    $f->json()->filter('permission');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/admin/{$this->getId()}/avatar", $has_avatar
            , $mf)->filter('avatar');
    //
    $mf->move();
    //
    $this->dao_update();

  }

  public function getList ()
  {
    $arrCriteria = array(
        'name LIKE ?' => '%' . $this->_filters['name'] . '%',
        'email LIKE ?' => '%' . $this->_filters['email'] . '%',
        'id_admin <> ?' => self::$_master_id
    );

    $select = new Select('admin');
    $select->addField('id_admin');
    $select->addField('active');
    $select->addField('name');
    $select->addField('email');
    $select->addField('inc_date');
    $select->where($arrCriteria);
    $select->order_by('name');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
    }

    return $result;

  }

  public function formatFilters ()
  {
    $this->_filters['name'] = Html::scape($this->_filters['name']);
    $this->_filters['email'] = Html::scape($this->_filters['email']);

    return $this->_filters;

  }

}

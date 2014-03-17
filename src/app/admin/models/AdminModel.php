<?php

namespace src\app\admin\models;

use src\app\admin\helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\PermissionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Form;

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
    $this->setTable('admin');
  }

  protected function formatTable ( $table )
  {
    $table['name'] = Html::scape($table['name']);
    $table['avatar_uploader'] = Form::Upload('avatar', $table['avatar'], 'image');

    $permission = new PermissionModel;
    $permission_listbox = $permission->getArrayList();
    $table['permission'] = Form::Listbox('permission', $permission_listbox, json_decode($table['permission']));

    return $table;
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

<?php

namespace src\app\admin\models;

use src\app\admin\helpers\PaginatorAdmin;
use Din\DataAccessLayer\Select;
use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\PermissionModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Form;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\DBValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\filters\TableFilter;
use Din\Exception\JsonException;

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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    $str_validator->validateRequiredEmail('email', 'E-mail');
    $str_validator->validateRequiredString('password', 'Senha');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'admin');
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    $upl_validator = new UploadValidator($input);
    $has_avatar = $upl_validator->validateFile('avatar');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_admin');
    $filter->setIntval('active');
    $filter->setTimestamp('inc_date');
    $filter->setString('name');
    $filter->setString('email');
    $filter->setCrypted('password');
    $filter->setJson('permission');
    //
    $mf = new MoveFiles;
    $filter->setUploaded('avatar', "/system/uploads/admin/{$this->getId()}/avatar", $has_avatar, $mf);
    //
    $mf->move();
    //
    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', 'Nome');
    $str_validator->validateRequiredEmail('email', 'E-mail');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'admin');
    $db_validator->setId('id_admin', $this->getId());
    $db_validator->validateUniqueValue('email', 'E-mail');
    //
    $upl_validator = new UploadValidator($input);
    $has_avatar = $upl_validator->validateFile('avatar');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setIntval('active');
    $filter->setString('name');
    $filter->setString('email');
    $filter->setCrypted('password');
    $filter->setJson('permission');
    //
    $mf = new MoveFiles;
    $filter->setUploaded('avatar', "/system/uploads/admin/{$this->getId()}/avatar", $has_avatar, $mf);
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

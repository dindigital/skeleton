<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\AdminValidator as validator;
use src\app\admin\models\essential\AdminAuthModel;
use src\app\admin\models\AdminModel;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class ConfigModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('admin');
  }

  public function update ( $id, $info )
  {
    $validator = new validator($this->_table);
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password']);
    $mf = new MoveFiles;
    $validator->setFile('avatar', $info['avatar'], $id, $mf);
    $validator->throwException();

    $mf->move();

    $admin_model = new AdminModel;
    $tableHistory = $admin_model->getById($id);
    $this->_dao->update($this->_table, array('id_admin = ?' => $id));
    $this->log('U', $info['name'], $this->_table, $tableHistory, 'Admin');

    $this->relogin($id);
  }

  public function relogin ( $id )
  {
    $admin_model = new AdminModel;
    $admin = $admin_model->getById($id);

    $aam = new AdminAuthModel;
    $aam->login($admin['email'], $admin['password'], true);
  }

}

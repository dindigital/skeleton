<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\AdminValidator as validator;
use src\app\admin\models\essential\AdminAuthModel;
use src\app\admin\models\AdminModel;

/**
 *
 * @package app.models
 */
class ConfigModel extends BaseModelAdm
{

  public function update ( $id, $info )
  {
    $validator = new validator;
    $validator->setName($info['name']);
    $validator->setEmail($info['email']);
    $validator->setPassword($info['password']);

    $validator->setFile('avatar', $info['avatar'], $id, false);
    $validator->throwException();

    $admin_model = new AdminModel;
    $tableHistory = $admin_model->getById($id);
    $this->_dao->update($validator->getTable(), array('id_admin = ?' => $id));
    $this->log('U', $info['name'], $validator->getTable(), $tableHistory, 'Admin');

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

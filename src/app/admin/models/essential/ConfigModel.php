<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\AdminAuthModel;
use src\app\admin\models\AdminModel;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class ConfigModel extends AdminModel
{

  public function update ( $input )
  {
    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setDao($this->_dao);
    $validator->setId($this->getId());
    $validator->setRequiredString('name', 'Nome');
    $validator->setEmail('email', 'E-mail');
    $validator->setPassword('password', 'Senha', false);

    $mf = new MoveFiles;
    $validator->setFile('avatar', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_update();
    $this->relogin();
  }

  public function relogin ()
  {
    $admin = $this->getById($this->getId());

    $aam = new AdminAuthModel;
    $aam->login($admin['email'], $admin['password'], true);
  }

}

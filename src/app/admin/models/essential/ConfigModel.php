<?php

namespace src\app\admin\models\essential;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\AdminAuthModel;
use src\app\admin\models\AdminModel;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\helpers\Form;

/**
 *
 * @package app.models
 */
class ConfigModel extends AdminModel
{

  public function formatTable ( $table )
  {
    $table['avatar_uploader'] = Form::Upload('avatar', $table['avatar'], 'image', false);

    return $table;
  }

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

    // deleta o arquivo antigo caso exista e tenha upload novo
    $row = $this->getById();
    if ( $this->_table->avatar && $row['avatar'] ) {
      $destiny = 'public/' . $row['avatar'];
      @unlink($destiny);
    }


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

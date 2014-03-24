<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\AdminAuthModel;
use src\app\admin\models\AdminModel;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\helpers\Form;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\filters\TableFilter;
use Din\Exception\JsonException;

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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('name', "Nome");
    $str_validator->validateRequiredEmail('email', "E-mail");
    //
    $upl_validator = new UploadValidator($input);
    $has_avatar = $upl_validator->validateFile('avatar');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setString('name');
    $filter->setString('email');
    $filter->setCrypted('password');
    //
    $mf = new MoveFiles;
    if ( $has_avatar ) {
      $filter->setUploaded('avatar', "/system/uploads/admin/{$this->getId()}/avatar");
      $mf->addFile($input['avatar'][0]['tmp_name'], $this->_table->avatar);
    }
    $mf->move();
    //
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

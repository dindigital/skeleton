<?php

namespace Admin\Models\Essential;

use Admin\Models\Essential\AdminAuthModel;
use Admin\Models\AdminModel;
use Admin\Helpers\MoveFiles;
use Admin\Helpers\Form;
use Admin\Custom_filter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class ConfigModel extends AdminModel
{

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['avatar'] = null;
    }

    $table['avatar_uploader'] = Form::Upload('avatar', $table['avatar'], 'image', false);

    return $table;

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('name', 'Nome');
    $v->stringEmail()->validate('email', 'E-mail');
    $has_avatar = $v->upload()->validate('avatar', 'Avatar');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('name');
    $f->string()->filter('email');
    $f->crypted()->filter('password');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/admin/{$this->getId()}/avatar", $has_avatar
            , $mf)->filter('avatar');
    //
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

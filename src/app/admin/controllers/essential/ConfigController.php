<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\AdminModel as model;
use src\app\admin\models\essential\AdminAuthModel;
use Din\Http\Post;
use src\app\admin\helpers\Form;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class ConfigController extends BaseControllerAdm
{

  private $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model();
  }

  public function get_save ()
  {
    $this->_data['table'] = $this->_data['admin'];
    $this->_data['table']['avatar'] = Form::Upload('avatar', $this->_data['table']['avatar'], 'imagem', false);

    $this->setSaveTemplate('essential/config_save.phtml');
  }

  public function post_save ()
  {
    try {
      $id_admin = $this->_data['admin']['id_admin'];

      $this->_model->save_config($id_admin, array(
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'password' => Post::text('password'),
          'avatar' => Post::upload('avatar'),
      ));

      $this->setSavedMsgSession();

      $admin = $this->_model->getById($id_admin);

      $aam = new AdminAuthModel;
      $aam->login($admin['email'], $admin['password'], true);

      JsonViewHelper::redirect('/admin/config/save/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

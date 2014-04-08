<?php

namespace src\app\admin\controllers;

use src\app\admin\models\AdminModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class AdminSaveController extends BaseControllerAdm
{

  protected $_model;
  protected $_id;

  public function __construct ( $id = null )
  {
    $this->_id = $id;
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get ()
  {
    $this->defaultSavePage('admin_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'password' => Post::text('password'),
          'avatar' => Post::upload('avatar'),
          'avatar_delete' => Post::checkbox('avatar_delete'),
          'permission' => Post::aray('permission'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

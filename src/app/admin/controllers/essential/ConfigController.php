<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\ConfigModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class ConfigController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  public function get_save ()
  {
    $this->_data['table'] = $this->_model->formatTable($this->_data['admin']);

    $this->setSaveTemplate('essential/config_save.phtml');
  }

  public function post_save ()
  {
    try {
      $id = $this->_data['admin']['id_admin'];

      $info = array(
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'password' => Post::text('password'),
          'avatar' => Post::upload('avatar'),
          'avatar_delete' => Post::checkbox('avatar_delete'),
      );

      $this->_model->setId($id);
      $this->_model->update($info);

      $this->setSavedMsgSession();
      JsonViewHelper::redirect('/admin/config/save/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

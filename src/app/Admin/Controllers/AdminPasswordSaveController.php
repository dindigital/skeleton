<?php

namespace Admin\Controllers;

use Din\Mvc\Controller\BaseController;
use Din\Essential\Models\AdminPasswordModel as model;
use Din\Http\Post;
use Exception;
use Helpers\JsonViewHelper;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class AdminPasswordSaveController extends BaseController
{

  protected $_model;
  protected $_token;

  public function __construct ( $token = null )
  {
    parent::__construct();
    $this->_model = new model;
    $this->_token = $token;

  }

  public function get ()
  {
    $this->_view->addFile('src/app/Admin/Views/layouts/login.phtml');
    $this->_view->addFile('src/app/Admin/Views/essential/recover_password.phtml', '{$CONTENT}');
    $this->display_html();

  }

  public function post ()
  {
    $input = array(
        'token' => $this->_token,
        'password' => Post::text('password'),
        'password2' => Post::text('password2')
    );

    try {
      $this->_model->update_password($input);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    $session = new Session('adm_session');
    $session->set('saved_msg', 'Senha alterada com sucesso');

    JsonViewHelper::redirect('/admin/');

  }

}

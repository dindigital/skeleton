<?php

namespace src\app\admin\controllers\essential;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\essential\AdminPasswordModel as model;
use Din\Http\Post;
use Exception;
use Din\ViewHelpers\JsonViewHelper;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class AdminPasswordController extends BaseController
{

  private $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  public function post_recover_password ()
  {
    $email = Post::text('email');

    try {
      $this->_model->recover_password($email);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    JsonViewHelper::display_success_message('E-mail enviado com sucesso, por favor acesse sua conta de e-mail para gerar uma nova senha');
  }

  public function get_update ()
  {
    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/admin/views/layouts/login.phtml');
    $this->_view->addFile('src/app/admin/views/essential/recover_password.phtml', '{$CONTENT}');
    $this->display_html();
  }

  public function post_update ( $token )
  {
    $data = array(
        'token' => $token,
        'password' => Post::text('password'),
        'password2' => Post::text('password2')
    );

    try {
      $this->_model->update_password($data);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    $session = new Session('adm_session');
    $session->set('saved_msg', 'Senha alterada com sucesso');

    JsonViewHelper::redirect('/admin/');
  }

}

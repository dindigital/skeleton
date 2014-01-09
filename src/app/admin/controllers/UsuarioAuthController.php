<?php

namespace src\app\admin\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\UsuarioAuthModel;
use Din\Http\Header;
use Din\Http\Post;
use \Exception;
use Din\Exception\JsonException;
use Din\ViewHelpers\JsonViewHelper;

/**
 *
 * @package app.controllers
 */
class UsuarioAuthController extends BaseController
{

  public function __construct ()
  {
    parent::__construct();
  }

  private function setAuthTemplate ()
  {
    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/admin/views/layouts/login.phtml');
  }

  public function get_index ()
  {
    $this->setAuthTemplate();
    $this->_view->addFile('src/app/admin/views/login.phtml', '{$CONTENT}');
    $this->display_html();
  }

  public function post_index ()
  {
    $email = Post::text('email');
    $senha = Post::text('senha');

    try {
      $usuarioAuthModel = new UsuarioAuthModel();
      $usuarioAuthModel->login($email, $senha);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    JsonViewHelper::redirect('/admin/index/index/');
  }

  public function get_logout ()
  {
    $usuarioAuthModel = new UsuarioAuthModel();
    $usuarioAuthModel->logout();

    Header::redirect('/admin/');
  }

}

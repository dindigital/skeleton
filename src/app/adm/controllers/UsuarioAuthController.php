<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioAuthModel;
use Din\Http\Header;
use Din\Http\Post;

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

    $this->_view->addFile('src/app/adm/views/login_layout.php');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

  public function get_index ()
  {
    $this->setAuthTemplate();
    $this->_view->addFile('src/app/adm/views/login.php', '{$CONTENT}');
    $this->display_html();
  }

  public function post_index ()
  {
    $email = Post::text('email');
    $senha = Post::text('senha');

    $usuarioAuthModel = new UsuarioAuthModel();
    $usuarioAuthModel->login($email, $senha);

    Header::redirect('/adm/index/index/');
    $this->display_html();
  }

  public function get_logout ()
  {
    $usuarioAuthModel = new UsuarioAuthModel();
    $usuarioAuthModel->logout();

    Header::redirect('/adm/');
  }

}

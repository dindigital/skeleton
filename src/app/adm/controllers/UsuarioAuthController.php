<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioAuthModel;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class UsuarioAuthController extends BaseController
{

  public function __construct ()
  {
    parent::__construct();

    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/adm/views/login_layout.php');
    $this->_view->addFile('src/app/adm/views/includes/head.php', '{$HEAD}');
    $this->_view->addFile('src/app/adm/views/includes/footer.php', '{$FOOTER}');
  }

  public function get_index ()
  {
    $this->_view->addFile('src/app/adm/views/login.php', '{$CONTENT}');
    $this->display_html();
  }

  public function post_index ()
  {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $memorizar = isset($_POST['memorizar']) ? '1' : '0';

    $usuarioAuthModel = new UsuarioAuthModel();
    $usuarioAuthModel->login($email, $senha, $memorizar);

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

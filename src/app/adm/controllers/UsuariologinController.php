<?php

namespace src\app\adm\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\adm\models\UsuarioLoginModel;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class UsuariologinController extends BaseController
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

    $usuariologinmodel = new UsuarioLoginModel();
    $usuariologinmodel->login($email, $senha, $memorizar);

    Header::redirect('/adm/index/index/');
    $this->display_html();
  }

  public function get_logout ()
  {
    $usuariologinmodel = new UsuarioLoginModel();
    $usuariologinmodel->logout();

    Header::redirect('/adm/');
  }

  public function get_login ()
  {
    try {

      $this->action = '/adm005/usuariologin/login/';

      $this->alljax_view('login.php');
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}

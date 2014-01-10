<?php

namespace src\app\admin\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\admin\models\UsuarioSenhaModel;
use Din\Http\Post;
use \Exception;
use Din\ViewHelpers\JsonViewHelper;
use Din\Email\Email;
use Din\Email\SendEmail\SendEmail;
use Din\Mvc\View\View;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class UsuarioSenhaController extends BaseController
{

  public function __construct ()
  {
    parent::__construct();
  }

  public function post_recuperar_senha ()
  {
    $email = Post::text('email');
    try {
      $usuarioSenhaModel = new UsuarioSenhaModel();
      $usuarioSenhaModel->recuperar_senha($email);
      $this->enviar_email($email, $usuarioSenhaModel->getToken());
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    JsonViewHelper::display_success_message('E-mail enviado com sucesso, por favor acesse sua conta de e-mail para gerar uma nova senha');
  }

  private function enviar_email ( $user_email, $token )
  {

    $data = array(
        'link' => URL . '/admin/usuario_senha/update/' . $token . '/'
    );

    $email_html = new View();
    $email_html->addFile('src/app/admin/views/email/recuperar_senha.phtml');
    $email_html->setData($data);

    $email = new Email;
    $email->setFrom('suporte@dindigital.com');
    $email->setTo($user_email);
    $email->setSubject('Recuperação de Senha - Painel de Controle');
    $email->setBody($email_html->getResult());

    $sendmail = new SendEmail($email);
    $sendmail->setHost(SMTP_HOST);
    $sendmail->setUser(SMTP_USER);
    $sendmail->setPass(SMTP_PASS);
    $sendmail->send();
  }

  public function get_update ()
  {
    $this->_data = array(
        'assets' => $this->getAssets(),
    );

    $this->_view->addFile('src/app/admin/views/layouts/login.phtml');
    $this->_view->addFile('src/app/admin/views/recuperar_senha.phtml', '{$CONTENT}');
    $this->display_html();
  }

  public function post_update ( $token )
  {
    $data = array(
        'token' => $token,
        'senha' => Post::text('senha'),
        'senha2' => Post::text('confirmar_senha')
    );

    try {
      $usuarioSenhaModel = new UsuarioSenhaModel();
      $usuarioSenhaModel->atualiza_senha($data);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

    $session = new Session('adm_session');
    $session->set('registro_salvo', 'Senha alterada com sucesso');

    JsonViewHelper::redirect('/admin/');
  }

}

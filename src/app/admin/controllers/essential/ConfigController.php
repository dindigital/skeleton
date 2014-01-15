<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\UsuarioModel as model;
use src\app\admin\models\UsuarioAuthModel;
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

  public function get_cadastro ()
  {
    $this->_data['table'] = $this->_data['user'];
    $this->_data['table']['avatar'] = Form::Upload('avatar', $this->_data['table']['avatar'], 'imagem', false);

    $this->setCadastroTemplate('config_cadastro.phtml');
  }

  public function post_cadastro ()
  {
    try {
      $id_usuario = $this->_data['user']['id_usuario'];

      $this->_model->salvar_config($id_usuario, array(
          'nome' => Post::text('nome'),
          'email' => Post::text('email'),
          'senha' => Post::text('senha'),
          'avatar' => Post::upload('avatar'),
      ));

      $this->setRegistroSalvoSession();

      $usuario = $this->_model->getById($id_usuario);
      $usuarioAuth = new UsuarioAuthModel;
      $usuarioAuth->login($usuario['email'], $usuario['senha'], true);

      JsonViewHelper::redirect('/admin/config/cadastro/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

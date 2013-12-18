<?php

namespace src\app\adm\controllers;

use src\app\adm\models\UsuarioModel;
use Din\Http\Post;
use src\app\adm\helpers\Upload;
use Din\Http\Header;

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
    $this->_model = new UsuarioModel();
  }

  public function get_cadastro ()
  {
    $this->_data['table'] = $this->_data['user'];
    $this->_data['table']['avatar'] = Upload::get('avatar', $this->_data['table']['avatar'], 'imagem', false);

    $this->setCadastroTemplate('config_cadastro.php');
  }

  public function post_cadastro ()
  {
    $id_usuario = $this->_data['user']['id_usuario'];

    $this->_model->salvar_config($id_usuario, array(
        'nome' => Post::text('nome'),
        'email' => Post::text('email'),
        'senha' => Post::text('senha'),
        'avatar' => Post::upload('avatar'),
    ));

    $this->setRegistroSalvoSession();
    Header::redirect();
  }

}

<?php

namespace src\app\admin\controllers;

use src\app\admin\models\UsuarioModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\admin\helpers\Form;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class UsuarioController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model();
    $this->setEntityData();
  }

  public function get_lista ()
  {
    $arrFilters = array(
        'nome' => Get::text('nome'),
        'email' => Get::text('email'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = $this->_model->listar($arrFilters, $paginator);
    $this->_data['busca'] = $arrFilters;

    $this->setErrorSessionData();

    $this->setListTemplate('usuario_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    if ( $id ) {
      $this->_data['table'] = $this->_model->getById($id);
    } else {
      $this->_data['table'] = array();
    }

    $this->_data['table']['avatar'] = Form::Upload('avatar', @$this->_data['table']['avatar'], 'imagem');
    $this->_data['table']['avatar2'] = Form::Upload('avatar2', @$this->_data['table']['avatar2'], 'imagem');
    $this->_data['table']['avatar3'] = Form::Upload('avatar3', @$this->_data['table']['avatar3'], 'imagem');

    $this->setCadastroTemplate('usuario_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'nome' => Post::text('nome'),
          'email' => Post::text('email'),
          'senha' => Post::text('senha'),
          'avatar' => Post::upload('avatar'),
          'avatar2' => Post::upload('avatar2'),
          'avatar3' => Post::upload('avatar3'),
      );

      if ( !$id ) {
        $id = $this->_model->inserir($info);
      } else {
        $this->_model->atualizar($id, $info);
      }

      $this->setRegistroSalvoSession();

      $redirect = '/admin/usuario/cadastro/' . $id . '/';
      if ( Post::text('redirect') == 'lista' ) {
        $redirect = '/admin/usuario/lista/';
      }

      JsonViewHelper::redirect($redirect);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

<?php

namespace src\app\adm\controllers;

use src\app\adm\models\UsuarioModel;
use src\app\adm\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\adm\helpers\Upload;
use Din\Http\Post;
use Din\Http\Header;

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
    $this->_model = new UsuarioModel();
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

    $this->setListTemplate('usuario_lista.php', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    if ( $id ) {
      $this->_data['table'] = $this->_model->getById($id);
    } else {
      $this->_data['table'] = array();
    }

    $this->_data['table']['avatar'] = Upload::get('avatar', @$this->_data['table']['avatar'], 'imagem');

    $this->setCadastroTemplate('usuario_cadastro.php');
  }

  public function post_cadastro ( $id = null )
  {
    $info = array(
        'ativo' => Post::checkbox('ativo'),
        'nome' => Post::text('nome'),
        'email' => Post::text('email'),
        'senha' => Post::text('senha'),
        'avatar' => Post::upload('avatar'),
    );

    if ( !$id ) {
      $id = $this->_model->inserir($info);
    } else {
      $this->_model->atualizar($id, $info);
    }

    $this->setRegistroSalvoSession();
    Header::redirect('/adm/usuario/cadastro/' . $id . '/');
  }

}

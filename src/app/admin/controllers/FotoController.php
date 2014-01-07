<?php

namespace src\app\admin\controllers;

use src\app\admin\models\FotoModel;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\admin\helpers\Form;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use \Exception;

/**
 *
 * @package app.controllers
 */
class FotoController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new FotoModel();
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

    $this->_data['table']['galeria_uploader'] = Form::Upload('galeria_uploader', @$this->_data['table']['galeria'], 'imagem', true);

    $this->setCadastroTemplate('foto_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'titulo' => Post::text('titulo'),
          'data' => Post::text('data'),
          'galeria_uploader' => Post::upload('galeria_uploader'),
      );

      if ( !$id ) {
        $id = $this->_model->inserir($info);
      } else {
        $this->_model->atualizar($id, $info);
      }

      $this->setRegistroSalvoSession();

      JsonViewHelper::redirect('/adm/foto/cadastro/' . $id . '/');
    } catch (Exception $e) {
      var_dump($e->getMessage());
      exit;
      JsonViewHelper::display_error_message($e);
    }
  }

}

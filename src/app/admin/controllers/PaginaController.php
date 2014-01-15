<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PaginaModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use \Exception;
use src\app\admin\helpers\Form;
use src\app\admin\models\PaginaCatModel;

/**
 *
 * @package app.controllers
 */
class PaginaController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();

    $this->_model = new model();
  }

  public function get_lista ()
  {

    $arrFilters = array(
        'titulo' => Get::text('titulo'),
        'id_pagina_cat' => Get::text('id_pagina_cat'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = $this->_model->listar($arrFilters, $paginator);
    $this->_data['busca'] = $arrFilters;

    $pagina_cat = new PaginaCatModel();
    $this->_data['busca']['id_pagina_cat'] = $pagina_cat->getDropdown('Filtro por Menu', @$this->_data['busca']['id_pagina_cat']);

    $this->setErrorSessionData();
    $this->setEntityData();

    $this->setListTemplate('pagina_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    if ( $id ) {
      $this->_data['table'] = $this->_model->getById($id);
    } else {
      $this->_data['table'] = array();
    }

    $this->_data['table']['capa'] = Form::Upload('capa', @$this->_data['table']['capa'], 'imagem');
    $this->_data['table']['conteudo'] = Form::Ck('conteudo', @$this->_data['table']['conteudo']);

    $pagina_cat = new PaginaCatModel();
    $this->_data['table']['id_pagina_cat'] = $pagina_cat->getDropdown('Selecione um Menu', @$this->_data['table']['id_pagina_cat']);

    $this->setEntityData();
    $this->setCadastroTemplate('pagina_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'id_pagina_cat' => Post::text('id_pagina_cat'),
          'titulo' => Post::text('titulo'),
          'capa' => Post::upload('capa'),
          'conteudo' => Post::text('conteudo'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
      );

      if ( !$id ) {
        $id = $this->_model->inserir($info);
      } else {
        $this->_model->atualizar($id, $info);
      }

      $this->setRegistroSalvoSession();

      $redirect = '/admin/pagina/cadastro/' . $id . '/';
      if ( Post::text('redirect') == 'lista' ) {
        $redirect = '/admin/pagina/lista/';
      }

      JsonViewHelper::redirect($redirect);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

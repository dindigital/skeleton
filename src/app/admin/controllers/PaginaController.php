<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PaginaModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use src\app\admin\helpers\Form;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\PaginaCatModel;
use src\app\admin\viewhelpers\PaginaViewHelper as vh;

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
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_lista ()
  {

    $arrFilters = array(
        'titulo' => Get::text('titulo'),
        'id_pagina_cat' => Get::text('id_pagina_cat'),
    );

    $pagina_cat = new PaginaCatModel();
    $pagina_cat_dropdown = $pagina_cat->getDropdown();

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters, $pagina_cat_dropdown);

    $this->setErrorSessionData();

    $this->setListTemplate('pagina_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    $exclude_previous = array(
        'capa'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($exclude_previous);

    $pagina_cat = new PaginaCatModel();
    $pagina_cat_dropdown = $pagina_cat->getDropdown();

    $this->_data['table'] = vh::formatRow($row, $pagina_cat_dropdown);

    $this->setCadastroTemplate('pagina_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
          'id_pagina_cat' => Post::text('id_pagina_cat'),
          'id_parent' => Post::aray('id_parent'),
          'titulo' => Post::text('titulo'),
          'capa' => Post::upload('capa'),
          'conteudo' => Post::text('conteudo'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
      );

      $this->saveAndRedirect($info, $id);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function get_ajax_intinify_cat ( $id_pagina_cat )
  {
    $dorpdown = $this->_model->getDropdown($id_pagina_cat, null);
    die(vh::format_infinity_dropdown($dorpdown));
  }

  public function get_ajax_infinity ( $id_parent )
  {
    $dorpdown = $this->_model->getDropdown(null, $id_parent);
    die(vh::format_infinity_dropdown($dorpdown));
  }

}

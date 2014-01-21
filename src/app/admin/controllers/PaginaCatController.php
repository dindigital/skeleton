<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PaginaCatModel as model;
use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\PaginaCatViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class PaginaCatController extends BaseControllerAdm
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
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters);

    $this->setErrorSessionData();

    $this->setListTemplate('paginacat_lista.phtml', $paginator);
  }

  public function get_cadastro ( $id = null )
  {
    $excluded_fields = array(
        'capa'
    );
    $row = $id ? $this->_model->getById($id) : $this->getPrevious($excluded_fields);

    $this->_data['table'] = vh::formatRow($row);

    $this->setCadastroTemplate('paginacat_cadastro.phtml');
  }

  public function post_cadastro ( $id = null )
  {
    try {
      $info = array(
          'ativo' => Post::checkbox('ativo'),
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

}

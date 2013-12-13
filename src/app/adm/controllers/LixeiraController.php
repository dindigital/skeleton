<?php

namespace src\app\adm\controllers;

use src\app\adm\BaseControllerAdm;
use src\app\adm\models\LixeiraModel;
use src\app\adm\objects\PaginatorPainel;
use src\app\adm\controllers\LogController;
use lib\Form\Post\Post;

/**
 *
 * @package app.controllers
 */
class LixeiraController extends BaseControllerAdm
{

  public function __construct ( $app_name, $Compressor )
  {
    try {

      $this->_model = new LixeiraModel();

      parent::__construct($app_name, $Compressor);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function get_lista ()
  {
    try {

      $arrCriteria = array();

      $this->busca->titulo = '';

      if ( isset($_GET['titulo']) && $_GET['titulo'] != '' ) {
        $arrCriteria['titulo'] = $_GET['titulo'];
        $this->busca->titulo = $_GET['titulo'];
      }

      $this->busca->secao = '';

      if ( isset($_GET['secao']) && $_GET['secao'] != '' && $_GET['secao'] != '0' ) {
        $arrCriteria['secao'] = $_GET['secao'];
        $this->busca->secao = $_GET['secao'];
      }

      $arrCriteria['id_entidade'] = $this->user_table->id_entidade;

      $this->busca->secao = $this->_model->getDropdown('Filtro por Seção', $this->busca->secao);

      $this->paginator = new PaginatorPainel(20, 7, @$_GET['pag']);

      $this->list = $this->_model->listar($arrCriteria, $this->paginator);
      $this->action = $this->uri->lista;

      $this->alljax_view($this->uri->view_lista);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_restaurar ()
  {
    try {

      $itens = Post::aray('itens');

      $list = array();
      foreach ( $itens as $i => $item ) {
        list($tbl, $id) = explode('_', $item);
        $itens[$i] = array('tbl' => $tbl, 'id' => $id);

        $model_name = "\\src\\app\\adm005\\models\\{$tbl}Model";
        $model = new $model_name;

        $responsavel = $this->user_table->nome;
        $secao = $model->getMe(true);
        $nome_legivel = $model->getMe();
        $tbl_instantce = $model->getById($id);
        $descricao = $tbl_instantce->{$tbl_instantce->getTitle()};

        LogController::inserir_manual($responsavel, $secao, $nome_legivel, 'R', $descricao);
      }

      $this->_model->restaurar($itens);

      //$_SERVER['HTTP_REFERER'] = 'http://tvabcd.local/adm005/lixeira/lista/?pag=2';
      $parse_url = parse_url($_SERVER['HTTP_REFERER']);
      parse_str(@$parse_url['query']);
      if ( isset($pag) ) {
        //@TODO: se a pagina for maior q 1 e não tiver resultados, subtrair...
      }

      $this->alljax_redirect($_SERVER['HTTP_REFERER']);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

  public function post_excluir ()
  {
    try {

      $id_entidade = $this->user_table->id_entidade;

      $itens = Post::aray('itens');

      $list = array();
      foreach ( $itens as $i => $item ) {
        list($tbl, $id) = explode('_', $item);
        $itens[$i] = array('tbl' => $tbl, 'id' => $id);

        $model_name = "\\src\\app\\adm005\\models\\{$tbl}Model";
        $model = new $model_name;

        $responsavel = $this->user_table->nome;
        $secao = $model->getMe(true);
        $nome_legivel = $model->getMe();
        $tbl_instantce = $model->getById($id);
        $descricao = $tbl_instantce->{$tbl_instantce->getTitle()};

        LogController::inserir_manual($responsavel, $secao, $nome_legivel, 'D', $descricao);
      }

      $model = new \src\app\adm005\models\LixeiraModel();
      $model->excluir($itens, $id_entidade);

      $this->alljax_redirect($_SERVER['HTTP_REFERER']);
    } catch (\Exception $e) {
      $this->alljax_exception($e);
    }
  }

}


<?php

namespace src\app\admin\controllers;

use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use \Exception;
use src\app\admin\models\LixeiraModel;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class LixeiraController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new LixeiraModel;
  }

  public function get_lista ()
  {
    $arrFilters = array(
        'titulo' => Get::text('titulo'),
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = $this->_model->listar($arrFilters, $paginator);
    $this->_data['busca'] = $arrFilters;

    $this->setErrorSessionData();

    $this->setListTemplate('lixeira_lista.phtml', $paginator);
  }

  public function post_restaurar ()
  {
    try {
      $itens = Post::aray('itens');

      $this->_model->restaurar($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

  public function post_excluir ()
  {
    try {
      $itens = Post::aray('itens');

      foreach ( $itens as $item ) {
        list($tbl, $id) = explode('_', $item);
        $tbl = ucfirst($tbl);
        $model_name = "\\src\\app\\admin\\models\\{$tbl}Model";
        $model = new $model_name;
        $model->excluir_permanente($id);
      }

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

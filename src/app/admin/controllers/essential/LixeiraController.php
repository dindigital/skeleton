<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\LixeiraModel as model;
use Din\Http\Header;
use src\app\admin\viewhelpers\LixeiraViewHelper as vh;

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
    $this->_model = new model;
  }

  public function get_lista ()
  {
    $arrFilters = array(
        'titulo' => Get::text('titulo'),
        'secao' => Get::text('secao')
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->listar($arrFilters, $paginator));
    $this->_data['busca'] = $arrFilters;

    $this->_data['secao'] = $this->_model->getDropdown('Filtro por Seção', $arrFilters['secao']);

    $this->setErrorSessionData();

    $this->setListTemplate('essential/lixeira_lista.phtml', $paginator);
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

      $this->_model->excluir($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

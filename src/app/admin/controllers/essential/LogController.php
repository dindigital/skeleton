<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\LogModel as model;
use Din\Http\Header;
use src\app\admin\models\essential\LixeiraModel;

/**
 *
 * @package app.controllers
 */
class LogController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  public function get_list ()
  {
    $arrFilters = array(
        'usuario' => Get::text('usuario'),
        'acao' => Get::text('acao'),
        'secao' => Get::text('secao'),
        'descricao' => Get::text('descricao')
    );

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = $this->_model->resultList($arrFilters, $paginator);
    $this->_data['busca'] = $arrFilters;
    $this->_data['dropdown']['acao'] = $this->_model->getDropdownAction('Filtro por Ação', $arrFilters['acao']);
    $this->_data['dropdown']['secao'] = $this->_model->getDropdownSecao('Filtro por Seção', $arrFilters['secao']);

    $this->setListTemplate('essential/log_lista.phtml', $paginator);
  }

  public function get_view ()
  {
    try {
      $itens = Post::aray('itens');

      $this->_model->restaurar($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

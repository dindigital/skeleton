<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\helpers\PaginatorPainel;
use Din\Http\Get;
use Din\Http\Post;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\TrashModel as model;
use Din\Http\Header;
use src\app\admin\viewhelpers\TrashViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class TrashController extends BaseControllerAdm
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
        'title' => Get::text('title'),
        'section' => Get::text('section')
    );

    $dropdown_secao = $this->_model->getListArray();

    $paginator = new PaginatorPainel(20, 7, Get::text('pag'));
    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters, $paginator));
    $this->_data['busca'] = vh::formatFilters($arrFilters, $dropdown_secao);

    $this->setErrorSessionData();

    $this->setListTemplate('essential/trash_list.phtml', $paginator);
  }

  public function post_restore ()
  {
    try {
      $itens = Post::aray('itens');

      $this->_model->restore($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

  public function post_delete ()
  {
    try {
      $itens = Post::aray('itens');

      $this->_model->delete($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Get;
use Din\Http\Post;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\TrashModel as model;
use Din\Http\Header;

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
        'section' => Get::text('section'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('essential/trash_list.phtml');
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

      $this->_model->delete_permanent($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

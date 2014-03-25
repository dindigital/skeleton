<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Get;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\LogModel as model;

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
        'admin' => Get::text('admin'),
        'action' => Get::text('action'),
        'tbl' => Get::text('tbl'),
        'description' => Get::text('description'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setListTemplate('essential/log_list.phtml');
  }

  public function get_save ( $id )
  {
    $this->_data['table'] = $this->_model->getById($id);
    $this->setSaveTemplate('essential/log_view.phtml');
  }

}

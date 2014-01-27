<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Get;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\essential\LogModel as model;
use src\app\admin\viewhelpers\LogViewHelper as vh;

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
        'name' => Get::text('name'),
        'description' => Get::text('description'),
        'pag' => Get::text('pag'),
    );

    $dropdownName = $this->_model->getDropdownName();

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters, $dropdownName);

    $this->setListTemplate('essential/log_list.phtml');
  }

  public function get_save ( $id )
  {
    try {
      $this->_data['table'] = vh::formatRow($this->_model->getById($id));
      $this->setSaveTemplate('essential/log_view.phtml');
    } catch (Exception $e) {

    }
  }

}

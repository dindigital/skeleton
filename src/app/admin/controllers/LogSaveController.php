<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\LogModel as model;

/**
 *
 * @package app.controllers
 */
class LogSaveController extends BaseControllerAdm
{

  protected $_model;
  protected $_id;

  public function __construct ( $id )
  {
    $this->_id = $id;
    parent::__construct();
    $this->_model = new model;

  }

  public function get ()
  {
    $this->_data['table'] = $this->_model->getById($this->_id);
    $this->setSaveTemplate('essential/log_view.phtml');

  }

}

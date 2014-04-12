<?php

namespace Admin\Controllers;

use Din\Http\Get;
use Admin\Models\Essential\TrashModel as model;

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

  public function get ()
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

}

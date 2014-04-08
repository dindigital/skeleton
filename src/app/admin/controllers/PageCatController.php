<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageCatModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class PageCatController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();

    $this->_model = new model;
    $this->setEntityData();
    //$this->require_permission();
  }

  public function get ()
  {

    $arrFilters = array(
        'title' => Get::text('title'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList($arrFilters);
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('pagecat_list.phtml');
  }

}

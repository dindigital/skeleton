<?php

namespace Admin\Controllers;

use Admin\Models\NewsSubModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class NewsSubAjaxController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();

  }

  public function get ()
  {
    $arrFilters = array(
        'id_news_cat' => Get::text('id'),
    );

    $dropdown = $this->_model->getListArray($arrFilters);

    die(json_encode($this->_model->formatDropdown($dropdown)));

  }

}

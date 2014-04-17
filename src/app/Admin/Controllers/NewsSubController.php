<?php

namespace Admin\Controllers;

use Admin\Models\NewsSubModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class NewsSubController extends BaseControllerAdm
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
        'title' => Get::text('title'),
        'id_news_cat' => Get::text('id_news_cat'),
        'pag' => Get::text('pag'),
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('newssub_list.phtml');

  }

}

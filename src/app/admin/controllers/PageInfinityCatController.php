<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class PageInfinityCatController extends BaseControllerAdm
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
    $id_page_cat = Get::text('id_page_cat');
    $exclude_id = Get::text('exclude_id');

    $dropdown = $this->_model->getListArray($id_page_cat, null, $exclude_id);
    die($this->_model->formatInfiniteDropdown($dropdown));
  }

}

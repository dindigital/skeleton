<?php

namespace Admin\Controllers;

use Admin\Models\PageModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class PageInfinityController extends BaseControllerAdm
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
    $id_parent = Get::text('id_parent');
    $exclude_id = Get::text('exclude_id');

    $dropdown = $this->_model->getListArray(null, $id_parent, $exclude_id);
    die($this->_model->formatInfiniteDropdown($dropdown));

  }

}

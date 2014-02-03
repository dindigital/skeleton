<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class ExemploController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
  }

  public function get_exemplos ()
  {
    $this->_view->addFile('src/app/admin/views/exemplos.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

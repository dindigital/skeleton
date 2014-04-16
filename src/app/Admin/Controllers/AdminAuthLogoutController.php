<?php

namespace Admin\Controllers;

use Din\Mvc\Controller\BaseController;
use Din\Essential\Models\AdminAuthModel as model;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class AdminAuthLogoutController extends BaseController implements \Respect\Rest\Routable
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;

  }

  public function get ()
  {
    $this->_model->logout();

    Header::redirect('/admin/');

  }

}

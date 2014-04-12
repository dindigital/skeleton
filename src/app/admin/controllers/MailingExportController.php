<?php

namespace src\app\admin\controllers;

use src\app\admin\models\MailingExportModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class MailingExportController extends BaseControllerAdm
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
        'name' => Get::text('name'),
        'email' => Get::text('email'),
        'mailing_group' => Get::text('mailing_group'),
    );

    $this->_model->getXls($arrFilters);

    $this->setErrorSessionData();

  }

}

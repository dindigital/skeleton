<?php

namespace src\app\admin\controllers;

use src\app\admin\models\MailingGroupModel as model;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class MailingGroupController extends BaseControllerAdm
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
        'name' => Get::text('name'),
        'pag' => Get::text('pag')
    );

    $this->_model->setFilters($arrFilters);
    $this->_data['list'] = $this->_model->getList();
    $this->_data['search'] = $this->_model->formatFilters();

    $this->setErrorSessionData();

    $this->setListTemplate('mailing_group_list.phtml');
  }

}

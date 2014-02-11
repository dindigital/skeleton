<?php

namespace src\app\admin\controllers;

use src\app\admin\models\MailingModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\viewhelpers\MailingViewHelper as vh;
use src\app\admin\models\MailingGroupModel;

/**
 *
 * @package app.controllers
 */
class MailingController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_list ()
  {
    $arrFilters = array(
        'name' => Get::text('name'),
        'email' => Get::text('email'),
        'mailing_group' => Get::text('mailing_group'),
        'pag' => Get::text('pag')
    );

    $mg = new MailingGroupModel;
    $mg_list = $mg->getListArray();

    $this->_data['list'] = vh::formatResult($this->_model->getList($arrFilters));
    $this->_data['search'] = vh::formatFilters($arrFilters, $mg_list);

    $this->setErrorSessionData();

    $this->setListTemplate('mailing_list.phtml');
  }

  public function get_save ( $id = null )
  {
    $this->_model->setId($id);

    $row = $id ? $this->_model->getById() : $this->getPrevious();
    $this->_data['table'] = vh::formatRow($row);

    $this->setSaveTemplate('mailing_save.phtml');
  }

  public function post_save ( $id = null )
  {
    try {
      $this->_model->setId($id);

      $info = array(
          'name' => Post::text('name'),
          'email' => Post::text('email'),
          'mailing_group' => Post::text('mailing_group'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

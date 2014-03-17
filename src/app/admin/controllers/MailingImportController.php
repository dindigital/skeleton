<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\MailingImportModel as model;
use Din\Http\Post;
use Din\Session\Session;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class MailingImportController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->require_permission();
  }

  public function get_xls ( $id = null )
  {
    $this->_data['table'] = $this->_model->createFields();

    $this->setSaveTemplate('mailing_import.phtml');
  }

  public function post_xls ()
  {
    try {
      $info = array(
          'xls' => Post::upload('xls'),
          'mailing_group' => Post::text('mailing_group'),
      );

      $report = $this->_model->import_xls($info);

      $session = new Session('adm_session');
      $session->set('saved_msg', $report);

      JsonViewHelper::redirect('/admin/mailing/list/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

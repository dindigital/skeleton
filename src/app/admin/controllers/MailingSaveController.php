<?php

namespace src\app\admin\controllers;

use src\app\admin\models\MailingModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class MailingSaveController extends BaseControllerAdm
{

  protected $_model;
  protected $_id;

  public function __construct ( $id )
  {
    $this->_id = $id;
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get ()
  {
    $this->defaultSavePage('mailing_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

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

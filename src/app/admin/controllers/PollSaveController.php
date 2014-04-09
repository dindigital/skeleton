<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PollModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class PollSaveController extends BaseControllerAdm
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
    $this->defaultSavePage('poll_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'question' => Post::text('question'),
          'uri' => Post::text('uri'),
          'option' => Post::aray('option'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

<?php

namespace Admin\Controllers;

use Admin\Models\SurveyModel as model;
use Din\Http\Post;
use Helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class SurveySaveController extends BaseControllerAdm
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
    $this->defaultSavePage('survey_save.phtml', $this->_id);

  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'uri' => Post::text('uri'),
          'question' => Post::aray('question'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

  }

}

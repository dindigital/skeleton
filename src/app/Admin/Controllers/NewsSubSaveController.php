<?php

namespace Admin\Controllers;

use Admin\Models\NewsSubModel as model;
use Din\Http\Post;
use Helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class NewsSubSaveController extends BaseControllerAdm
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
    $this->defaultSavePage('newssub_save.phtml', $this->_id);

  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'is_active' => Post::checkbox('is_active'),
          'id_news_cat' => Post::text('id_news_cat'),
          'title' => Post::text('title'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

  }

}

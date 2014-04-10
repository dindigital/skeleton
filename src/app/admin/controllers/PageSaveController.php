<?php

namespace src\app\admin\controllers;

use src\app\admin\models\PageModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class PageSaveController extends BaseControllerAdm
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
    $this->defaultSavePage('page_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'id_page_cat' => Post::text('id_page_cat'),
          'id_parent' => Post::aray('id_parent'),
          'title' => Post::text('title'),
          'cover' => Post::upload('cover'),
          'cover_delete' => Post::checkbox('cover_delete'),
          'content' => Post::text('content'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
          'uri' => Post::text('uri'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

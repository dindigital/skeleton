<?php

namespace src\app\admin\controllers;

use src\app\admin\models\NewsModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class NewsSaveController extends BaseControllerAdm
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
    $this->defaultSavePage('news_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'id_news_cat' => Post::text('id_news_cat'),
          'title' => Post::text('title'),
          'date' => Post::text('date'),
          'head' => Post::text('head'),
          'body' => Post::text('body'),
          'uri' => Post::text('uri'),
          'cover_delete' => Post::checkbox('cover_delete'),
          'cover' => Post::upload('cover'),
          'r_news_photo' => Post::aray('r_news_photo'),
          'r_news_video' => Post::aray('r_news_video'),
          'photo' => Post::text('photo'),
          'video' => Post::text('video')
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

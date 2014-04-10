<?php

namespace src\app\admin\controllers;

use src\app\admin\models\VideoModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class VideoSaveController extends BaseControllerAdm
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
    $this->defaultSavePage('video_save.phtml', $this->_id);
  }

  public function post ()
  {
    try {
      $this->_model->setId($this->_id);

      $info = array(
          'active' => Post::checkbox('active'),
          'title' => Post::text('title'),
          'date' => Post::text('date'),
          'description' => Post::text('description'),
          'link_youtube' => Post::text('link_youtube'),
          'link_vimeo' => Post::text('link_vimeo'),
          'uri' => Post::text('uri'),
          'tag' => Post::text('tag'),
          'file' => Post::upload('file'),
          'file_delete' => Post::checkbox('file_delete'),
          'publish_youtube' => Post::checkbox('publish_youtube'),
          'republish_youtube' => Post::checkbox('republish_youtube'),
      );

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

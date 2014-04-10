<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\CacheModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class CacheSaveController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  public function get ()
  {
    $this->setSaveTemplate('essential/cache_save.phtml');
  }

  public function post ()
  {
    try {


      $input = array(
          'all' => Post::checkbox('all'),
          'home' => Post::checkbox('home'),
          'uri' => Post::text('uri'),
      );

      $this->_model->clear($input);

      $this->setSavedMsgSession();
      JsonViewHelper::redirect('/admin/cache/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

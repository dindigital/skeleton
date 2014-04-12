<?php

namespace Admin\Controllers;

use Admin\Models\Essential\CacheModel as model;
use Din\Http\Post;
use Helpers\JsonViewHelper;
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
      JsonViewHelper::redirect('/admin/cache/save/');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }

  }

}

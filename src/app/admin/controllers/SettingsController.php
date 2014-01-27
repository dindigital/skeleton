<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\SettingsModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\viewhelpers\SettingsViewHelper as vh;

/**
 *
 * @package app.controllers
 */
class SettingsController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model();
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_save ()
  {
    $this->_data['table'] = vh::formatRow($this->_model->getById('1'));
    $this->setSaveTemplate('settings_save.phtml');
  }

  public function post_save ()
  {
    try {

      $info = array(
          'home_title' => Post::text('home_title'),
          'home_description' => Post::text('home_description'),
          'home_keywords' => Post::text('home_keywords'),
          'title' => Post::text('title'),
          'description' => Post::text('description'),
          'keywords' => Post::text('keywords'),
      );

      $this->saveAndRedirect($info, '1');
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

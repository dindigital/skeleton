<?php

namespace src\app\admin\controllers;

use src\app\admin\models\SettingsModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class SettingsSaveController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get ()
  {
    $this->_data['table'] = $this->_model->getRow('1');
    $this->setSaveTemplate('settings_save.phtml');
  }

  public function post ()
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
      $this->_model->setId('1');

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

<?php

namespace src\app\admin\controllers;

use src\app\admin\controllers\essential\BaseControllerAdm;
use src\app\admin\models\SocialmediaCredentialsModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;

/**
 *
 * @package app.controllers
 */
class SocialmediaCredentialsController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
    $this->setEntityData();
    $this->require_permission();
  }

  public function get_save ()
  {
    $this->_data['table'] = $this->_model->getById('1');
    $this->setSaveTemplate('socialmedia_credentials_save.phtml');
  }

  public function post_save ()
  {
    try {

      $info = array(
          'fb_app_id' => Post::text('fb_app_id'),
          'fb_app_secret' => Post::text('fb_app_secret'),
          'fb_page' => Post::text('fb_page'),
          'fb_access_token' => Post::text('fb_access_token'),
          'tw_consumer_key' => Post::text('tw_consumer_key'),
          'tw_consumer_secret' => Post::text('tw_consumer_secret'),
          'tw_access_token' => Post::text('tw_access_token'),
          'tw_access_secret' => Post::text('tw_access_secret'),
          'issuu_key' => Post::text('issuu_key'),
          'issuu_secret' => Post::text('issuu_secret'),
          'sc_client_id' => Post::text('sc_client_id'),
          'sc_client_secret' => Post::text('sc_client_secret'),
          'sc_token' => Post::text('sc_token'),
          'youtube_id' => Post::text('youtube_id'),
          'youtube_secret' => Post::text('youtube_secret'),
          'youtube_token' => Post::text('youtube_token'),
      );

      $this->_model->setId('1');

      $this->saveAndRedirect($info);
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

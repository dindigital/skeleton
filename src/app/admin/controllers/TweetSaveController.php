<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\TweetModel as model;
use Din\Http\Post;
use src\helpers\JsonViewHelper;
use Exception;
use Din\Http\Header;
use Din\Session\Session;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class TweetSaveController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();

    $section = Get::text('section');
    $id = Get::text('id');

    $this->_model = new model($section, $id);
  }

  public function get ()
  {
    $this->_data['redirect'] = Header::getReferer();
    $this->_data['tweet'] = $this->_model->generateTweet();

    $this->setSaveTemplate('essential/tweet_edit.phtml');
  }

  public function post ()
  {
    try {

      $this->_model->sendTweet(Post::text('msg'));

      $session = new Session('adm_session');
      $session->set('saved_msg', 'Registro twitado com sucesso!');

      JsonViewHelper::redirect(Post::text('redirect'));
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

}

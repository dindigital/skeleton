<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\TweetModel as model;
use Din\Http\Post;
use Din\ViewHelpers\JsonViewHelper;
use Exception;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Din\Http\Header;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class TweetController extends BaseControllerAdm
{

  protected $_model;

  protected function setModel ( $section, $id )
  {
    $this->_model = new model($section, $id);
  }

  public function get_edit ( $section, $id )
  {
    $this->setModel($section, $id);

    $this->_data['redirect'] = Header::getReferer();
    $this->_data['tweet'] = $this->_model->generateTweet();

    $this->setSaveTemplate('tweet_edit.phtml');
  }

  public function post_edit ( $section, $id )
  {
    try {

      $this->setModel($section, $id);
      $this->_model->sendTweet(Post::text('msg'));

      $session = new Session('adm_session');
      $session->set('saved_msg', 'Registro twitado com sucesso!');

      JsonViewHelper::redirect(Post::text('redirect'));
    } catch (Exception $e) {
      JsonViewHelper::display_error_message($e);
    }
  }

  public function get_view ( $section, $id )
  {
    $this->setModel($section, $id);

    $this->_data['redirect'] = Header::getReferer();
    $this->_data['tweets'] = $this->_model->getTweets();

    $this->setSaveTemplate('tweet_view.phtml');
  }

}

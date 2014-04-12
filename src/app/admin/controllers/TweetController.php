<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\TweetModel as model;
use Din\Http\Header;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class TweetController extends BaseControllerAdm
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
    $this->_data['tweets'] = $this->_model->getTweets();

    $this->setSaveTemplate('essential/tweet_view.phtml');

  }

}

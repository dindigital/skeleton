<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\YouTubeModel as model;
use Din\Http\Get;
use Din\Http\Header;
use src\app\admin\controllers\essential\BaseControllerAdm;

use Exception;
use Google_Client;
use Google_Service_YouTube_VideoSnippet;
use Google_Service_YouTube_Video;
use Google_Service_YouTube_VideoStatus;
use Google_Service_YouTube;
use Google_Service_Exception;

/**
 *
 * @package app.controllers
 */
class YouTubeController extends BaseControllerAdm
{

  protected $_model;

  public function get_auth ()
  {
    $this->_model = new model();
    $code = Get::text('code');
    $this->_model->auth($code);
    Header::redirect('/admin/index/index/');
  }

}

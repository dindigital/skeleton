<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\SoundCloudModel as model;
use Din\Http\Get;
use Din\Http\Header;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class SoundCloudController extends BaseControllerAdm
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

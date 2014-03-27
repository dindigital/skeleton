<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\SoundCloudModel as model;
use Din\Http\Get;
use src\app\admin\controllers\essential\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class SoundCloudController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;
  }

  public function get_auth ()
  {
    $this->_model->saveToken(array(
        'code' => Get::text('code')
    ));
  }

}

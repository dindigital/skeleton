<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\SoundCloudModel as model;
use Din\Http\Get;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Din\Session\Session;
use Din\Http\Header;

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

    $session = new Session('adm_session');
    $session->set('saved_msg', 'Atenticado no SoundCloud com sucesso');

    Header::redirect($session->get('referer'));
  }

}

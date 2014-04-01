<?php

namespace src\app\admin\controllers\essential;

use src\app\admin\models\essential\YouTubeModel as model;
use Din\Http\Get;
use Din\Http\Header;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Din\Session\Session;

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

    $session = new Session('adm_session');
    $session->set('saved_msg', 'Atenticado no Youtube com sucesso');

    Header::redirect($session->get('referer'));
  }

}

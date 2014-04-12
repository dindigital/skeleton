<?php

namespace Admin\Controllers;

use Admin\Models\Essential\SoundCloudModel as model;
use Din\Http\Get;
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

  public function get ()
  {
    $this->_model->saveToken(array(
        'code' => Get::text('code')
    ));

    $session = new Session('adm_session');
    $session->set('saved_msg', 'Atenticado no SoundCloud com sucesso');

    Header::redirect($session->get('referer'));

  }

}

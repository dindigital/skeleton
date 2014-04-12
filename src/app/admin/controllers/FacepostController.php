<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\FacepostModel as model;
use Din\Http\Header;
use Din\Http\Get;

/**
 *
 * @package app.controllers
 */
class FacepostController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()/* rou */
  {
    parent::__construct();

    $section = Get::text('section');
    $id = Get::text('id');

    $this->_model = new model($section, $id);

  }

  public function get ()
  {
    $this->_data['redirect'] = Header::getReferer();
    $this->_data['posts'] = $this->_model->getPosts();

    $this->setSaveTemplate('essential/facepost_view.phtml');

  }

}

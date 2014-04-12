<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\ActiveModel as model;
use Din\Http\Post;

/**
 *
 * @package app.controllers
 */
class ActiveSaveController implements \Respect\Rest\Routable
{

  protected $_model;

  public function __construct ()
  {
    $this->_model = new model;

  }

  public function post ()
  {
    $this->_model->setModelByTbl(Post::text('name'));
    $this->_model->toggleActive(Post::text('id'), Post::checkbox('active'));

  }

}

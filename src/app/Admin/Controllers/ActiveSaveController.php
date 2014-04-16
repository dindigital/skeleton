<?php

namespace Admin\Controllers;

use Din\Essential\Models\ActiveModel as model;
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

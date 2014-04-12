<?php

namespace src\app\admin\controllers;

use Din\Http\Post;
use Exception;
use src\app\admin\models\essential\TrashModel as model;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class TrashDeleteController extends BaseControllerAdm
{

  protected $_model;

  public function __construct ()
  {
    parent::__construct();
    $this->_model = new model;

  }

  public function post ()
  {
    try {
      $itens = Post::aray('itens');

      $this->_model->delete_permanent($itens);

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }

  }

}

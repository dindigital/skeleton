<?php

namespace src\app\admin\controllers;

use Din\Http\Post;
use Exception;
use Din\Http\Header;
use src\app\admin\models\essential\SequenceModel;

/**
 *
 * @package app.controllers
 */
class SequenceController extends BaseControllerAdm
{

  public function post ()
  {
    try {

      $model = new SequenceModel;
      $model->changeSequence(array(
          'tbl' => Post::text('name'),
          'id' => Post::text('id'),
          'sequence' => Post::text('sequence'),
      ));

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

<?php

namespace src\app\admin\controllers;

use Din\Http\Post;
use src\app\admin\helpers\Entities;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Exception;
use Din\Http\Header;
use src\app\admin\models\essential\SequenceModel;

/**
 *
 * @package app.controllers
 */
class SequenceController extends BaseControllerAdm
{

  public function post_change ()
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

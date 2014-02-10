<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Post;
use src\app\admin\helpers\Entities;
use src\app\admin\controllers\essential\BaseControllerAdm;
use Exception;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class SequenceController extends BaseControllerAdm
{

  public function post_change ()
  {
    try {
      Entities::readFile('config/entities.php');
      $current = Entities::getEntityByName(Post::text('name'));

      $model = new $current['model'];
      $model->changeSequence(Post::text('id'), Post::text('sequence'));

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

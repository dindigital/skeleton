<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Post;
use src\app\admin\helpers\Entities;
use Din\Http\Header;
use Exception;
use Din\Session\Session;

/**
 *
 * @package app.controllers
 */
class SequenceController
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
      $session = new Session('adm_session');
      $session->set('error', $e->getMessage());

      Header::redirect(Header::getReferer());
    }
  }

}

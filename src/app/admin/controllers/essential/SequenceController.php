<?php

namespace src\app\admin\controllers\essential;

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

  public function __construct ()
  {
    parent::__construct();
    Entities::readFile('config/entities.php');
  }

  public function post_change ()
  {
    try {
      $current = Entities::getEntityByName(Post::text('name'));

      $model = new $current['model'];
      $seq = new SequenceModel($model);
      $seq->changeSequence(Post::text('id'), Post::text('sequence'));

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }
  }

}

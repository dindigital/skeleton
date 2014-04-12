<?php

namespace src\app\admin\controllers;

use src\app\admin\models\essential\RelationshipModel as model;
use Din\Http\Get;
use Din\Http\Post;
use src\helpers\JsonViewHelper;

/**
 *
 * @package app.controllers
 */
class RelashionshipController implements \Respect\Rest\Routable
{

  protected $_model;

  public function __construct ()
  {
    $this->_model = new model;

  }

  public function get ()
  {
    $this->_model->setForeignEntity(Get::text('relationshipSection'));
    $result = $this->_model->getAjax(Get::text('q'));

    JsonViewHelper::display($result);

  }

  public function post ()
  {
    $this->_model->setCurrentEntity(Post::text('currentSection'));
    $this->_model->setForeignEntity(Post::text('relationshipSection'));
    $result = $this->_model->getAjaxCurrent(Post::text('id'));

    JsonViewHelper::display($result);

  }

}

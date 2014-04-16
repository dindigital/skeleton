<?php

namespace Admin\Controllers;

use Din\Essential\Models\RelationshipModel as model;
use Din\Http\Get;
use Din\Http\Post;
use Helpers\JsonViewHelper;

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

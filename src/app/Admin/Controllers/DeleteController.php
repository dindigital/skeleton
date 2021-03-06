<?php

namespace Admin\Controllers;

use Din\Essential\Helpers\Entities;
use Din\Http\Post;
use Din\Essential\Models\TrashModel;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class DeleteController extends BaseControllerAdm
{

  public function __construct ( $entity_name )
  {
    parent::__construct();

    $entities = new Entities('config/entities.php');
    $this->_entity = $entities->getEntity($entity_name);

  }

  public function post ()
  {
    try {
      $itens = Post::aray('itens');

      if ( $this->_entity->hasTrash() ) {
        $trash = new TrashModel();
        $trash->delete($itens);
      } else {
        $this->_entity->getModel()->delete($itens);
      }

      Header::redirect(Header::getReferer());
    } catch (Exception $e) {
      $this->setErrorSession($e->getMessage());
    }

  }

}

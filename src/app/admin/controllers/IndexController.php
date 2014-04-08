<?php

namespace src\app\admin\controllers;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerAdm
{

  public function get ()
  {
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/admin/views/essential/index.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

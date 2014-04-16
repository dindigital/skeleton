<?php

namespace Admin\Controllers;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerAdm
{

  public function get ()
  {
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/Admin/Views/essential/index.phtml', '{$CONTENT}');
    $this->display_html();

  }

}

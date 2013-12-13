<?php

namespace src\app\adm\controllers;

use src\app\adm\controllers\BaseControllerAdm;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerAdm
{

  public function get_index ()
  {
    $this->_view->addFile('src/app/adm/views/index.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

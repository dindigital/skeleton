<?php

namespace src\app\site\controllers;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerSite
{

  public function get_index ()
  {
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/site/views/index.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

<?php

namespace src\app\admin\controllers\essential;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerAdm
{

  public function get_index ()
  {
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/admin/views/essential/index.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

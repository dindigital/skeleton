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
    $this->_view->addFile('src/app/admin/views/index.phtml', '{$CONTENT}');
    $this->display_html();
  }

}

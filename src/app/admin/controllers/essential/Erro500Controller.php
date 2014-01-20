<?php

namespace src\app\admin\controllers\essential;

use Din\Http\Header;
use Din\Mvc\View\View;

/**
 *
 * @package app.controllers
 */
class Erro500Controller
{

  public function get_display ( $msg )
  {
    Header::set500();

    $view = new View();
    $view->setData(array(
        'msg' => $msg
    ));
    $view->addFile('src/app/admin/views/essential/500.phtml');
    $view->display_html();
  }

}

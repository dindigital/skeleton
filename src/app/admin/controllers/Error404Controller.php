<?php

namespace src\app\admin\controllers;

use Din\Http\Header;
use Din\Mvc\View\View;
use Respect\Rest\Routable;

/**
 *
 * @package app.controllers
 */
class Error404Controller implements Routable
{

  public function get ()
  {
    Header::set404();
    $view = new View;
    $view->addFile('src/app/admin/views/essential/404.phtml');
    $view->display_html();
  }

}

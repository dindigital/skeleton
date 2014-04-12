<?php

namespace Admin\Controllers;

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
    $view->addFile('src/app/Admin/Views/essential/404.phtml');
    $view->display_html();
  }

}

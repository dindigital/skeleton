<?php

namespace Site\Controllers;

use Site\Models as models;
use Din\Mvc\View\View;
use Respect\Rest\Routable;

/**
 *
 * @package app.controllers
 */
class NewsSitemapController implements Routable
{

  public function get ()
  {
    $model = new models\NewsModel;
    $data = $model->newsSitemap();

    $view = new View;
    $view->setData($data);
    $view->addFile('src/app/Site/Views/sitemap/sitemap.phtml');
    $view->display_xml();

  }

}

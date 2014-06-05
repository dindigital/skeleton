<?php

namespace Site\Controllers;

use Site\Models as models;
use Din\Mvc\View\View;
use Respect\Rest\Routable;

/**
 *
 * @package app.controllers
 */
class PageSitemapController implements Routable
{

  public function get ()
  {
    $model = new models\PageCatModel;
    $data = $model->pageSitemap();

    $view = new View;
    $view->setData($data);
    $view->addFile('src/app/Site/Views/sitemap/sitemap_pages.phtml');
    $view->display_xml();

  }

}

<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class PageController extends BaseControllerSite
{

  public function get ( $uri_cat, $uri )
  {

    $model = new models\PageModel;
    $this->_data = $model->pageView($uri_cat, $uri);

    /**
     * Define template e exibição
     */
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/Site/Views/page.phtml', '{$CONTENT}');
    $this->display_html();

  }

}

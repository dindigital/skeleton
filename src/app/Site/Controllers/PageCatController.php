<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class PageCatController extends BaseControllerSite
{

  public function get ( $uri )
  {

    $model = new models\PageCatModel;
    $this->_data = $model->pageCatView($uri);

    /**
     * Define template e exibição
     */
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/Site/Views/page.phtml', '{$CONTENT}');
    $this->display_html();

  }

}

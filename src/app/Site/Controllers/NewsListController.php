<?php

namespace Site\Controllers;

use Din\Http\Header;
use Din\Http\Get;
use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class NewsListController extends BaseControllerSite
{

  public function get ()
  {


    $model = new models\NewsModel;
    $this->_data = $model->newsList(Get::text('pag'));

    /**
     * Define template e exibição
     */
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/Site/Views/news_list.phtml', '{$CONTENT}');
    $this->display_html();

  }

}

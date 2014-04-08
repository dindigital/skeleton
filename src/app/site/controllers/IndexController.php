<?php

namespace src\app\site\controllers;

use src\app\site\models as models;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerSite implements \Respect\Rest\Routable
{

  public function get ()
  {
    $cache_name = 'index';
    $html = $this->_viewcache->get($cache_name);

    if ( is_null($html) ) {

      /**
       * Últimas notícias
       */
      $newsModel = new models\NewsModel();
      $this->_data['news'] = $newsModel->getListHome();

      /**
       * Define template e exibição
       */
      $this->setBasicTemplate();
      $this->_view->addFile('src/app/site/views/index.phtml', '{$CONTENT}');
      $html = $this->return_html();
      $this->_viewcache->save($cache_name, $html);
    }

    $this->_view->display_html_result($html);
  }

}

<?php

namespace src\app\site\controllers;

use Din\Http\Header;
use src\app\site\models as models;

/**
 *
 * @package app.controllers
 */
class PageController extends BaseControllerSite
{

  public function get ( $uri_cat, $uri )
  {
    $cache_name = Header::getUri();
    $html = $this->_cache->get($cache_name);

    if ( is_null($html) ) {

      /**
       * Últimas notícias
       */
      $pageCatModel = new models\CacheModel(new models\PageModel(), $this->_cache, 180);
      $this->_data['page'] = $pageCatModel->getView($uri_cat, $uri);

      /**
       * Define template e exibição
       */
      $this->setBasicTemplate();
      $this->_view->addFile('src/app/site/views/page.phtml', '{$CONTENT}');
      $html = $this->return_html();
      $this->_cache->save($cache_name, $html);
    }

    $this->_view->display_html_result($html);

  }

}

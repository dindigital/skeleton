<?php

namespace Site\Controllers;

use Din\Http\Header;
use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class NewsController extends BaseControllerSite
{

  public function get ( $uri )
  {
    $cache_name = Header::getUri();
    $html = $this->_cache->get($cache_name);

    if ( is_null($html) ) {

      /**
       * Últimas notícias
       */
      $newsModel = new models\CacheModel(new models\NewsModel(), $this->_cache, 180);
      $this->_data['news'] = $newsModel->getView($uri);

      /**
       * Define template e exibição
       */
      $this->setBasicTemplate();
      $this->_view->addFile('src/app/Site/Views/news.phtml', '{$CONTENT}');
      $html = $this->return_html();
      $this->_cache->save($cache_name, $html);
    }

    $this->_view->display_html_result($html);

  }

}

<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerSite
{

  public function get ()
  {
    $cache_name = 'index';
    $html = $this->_cache->get($cache_name);

    if ( is_null($html) ) {

      /**
       * Últimas notícias
       */
      $newsModel = new models\CacheModel(new models\NewsModel(), $this->_cache, 180);
      $this->_data['news'] = $newsModel->getListHome();

      /**
       * Define template e exibição
       */
      $this->setBasicTemplate();
      $this->_view->addFile('src/app/Site/Views/index.phtml', '{$CONTENT}');
      $html = $this->return_html();
      $this->_cache->save($cache_name, $html);
    }

    $this->_view->display_html_result($html);

  }

}

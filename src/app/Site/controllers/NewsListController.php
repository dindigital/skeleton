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
    $cache_name = Header::getUri();
    $html = $this->_cache->get($cache_name);

    if ( is_null($html) ) {

      /**
       * Lista de Notícias
       */
      $newsModel = new models\NewsModel();
      $this->_data['news'] = $newsModel->getList(Get::text('pag'));

      /**
       * Pega itens da paginação
       */
      $paginator = $newsModel->getPaginator();
      $this->_data['paginator']['numbers'] = $paginator->getNumbers();

      /**
       * Define template e exibição
       */
      $this->setBasicTemplate();
      $this->_view->addFile('src/app/Site/Views/news_list.phtml', '{$CONTENT}');
      $html = $this->return_html();
      $this->_cache->save($cache_name, $html);
    }

    $this->_view->display_html_result($html);

  }

}

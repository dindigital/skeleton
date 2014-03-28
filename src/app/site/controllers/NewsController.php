<?php

namespace src\app\site\controllers;

use Din\Http\Header;
use src\app\site\models as models;

/**
 *
 * @package app.controllers
 */
class NewsController extends BaseControllerSite
{

  public function get_view ( $uri )
  {
    $cache_name = Header::getUri();
    $html = $this->_viewcache->get($cache_name);
        
    if (is_null($html)) {
        
        /**
         * Últimas notícias
         */
        $newsModel = new models\NewsModel();
        $this->_data['news'] = $newsModel->getView($uri);
        
        /**
         * Define template e exibição
         */
        $this->setBasicTemplate();
        $this->_view->addFile('src/app/site/views/news.phtml', '{$CONTENT}');
        $html = $this->return_html();
        $this->_viewcache->save($cache_name, $html);
    }
    
    $this->_view->display_html_result($html);
  }

  public function get_list ()
  {
    $cache_name = Header::getUri();
    $html = $this->_viewcache->get($cache_name);
        
    if (is_null($html)) {
        
        /**
         * Lista de Notícias
         */
        $newsModel = new models\NewsModel();
        $this->_data['news'] = $newsModel->getList();
        
        /**
         * Define template e exibição
         */
        $this->setBasicTemplate();
        $this->_view->addFile('src/app/site/views/news_list.phtml', '{$CONTENT}');
        $html = $this->return_html();
        $this->_viewcache->save($cache_name, $html);
    }
    
    $this->_view->display_html_result($html);
  }

}

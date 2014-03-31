<?php

namespace src\app\site\controllers;

use Din\Http\Header;
use Din\Http\Get;
use src\app\site\models as models;

/**
 *
 * @package app.controllers
 */
class NewsController extends BaseControllerSite
{
    
  protected $_model;

  public function __construct() {
      parent::__construct();
      $this->_model = new models\NewsModel();
  }

  public function get_view ( $uri )
  {
    $cache_name = Header::getUri();
    $html = $this->_viewcache->get($cache_name);
        
    if (is_null($html)) {
        
        /**
         * Últimas notícias
         */
        $this->_data['news'] = $this->_model->getView($uri);
        
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
        $this->_data['news'] = $this->_model->getList(Get::text('pag'));
        
        /**
         * Pega itens da paginação
         */
        $paginator = $this->_model->getPaginator();
        $this->_data['paginator']['numbers'] = $paginator->getNumbers();
        
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

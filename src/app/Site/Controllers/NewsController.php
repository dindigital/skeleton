<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class NewsController extends AbstractSiteController
{

    public function get ( $uri )
    {

        $url = "/noticias/{$uri}/";
        $html = $this->_cache->get($url);

        if ( is_null($html) || !CACHE_HTML ) {

            $model = new models\NewsModel;

            //trago os logos dos clientes
            $data = $model->getPage($url);

            $html = $this->_twig->render('news.html', $data);

            if ( CACHE_HTML )
                $this->_cache->save($cache_name, $html, 5 * 60);
        }

        return $html;

    }

}

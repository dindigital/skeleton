<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class NewsSitemapController extends AbstractSiteController
{

    public function get ()
    {

        $cache_name = 'news_sitemap';
        $html = $this->_cache->get($cache_name);

        if ( is_null($html) || !CACHE_HTML ) {

            $model = new models\Sitemap\News;
            $data = $model->getContents();

            $html = $this->_twig->render('sitemap/sitemap.xml', $data);

            if ( CACHE_HTML )
                $this->_cache->save($cache_name, $html, 5 * 60);
        }

        return $html;

    }

}

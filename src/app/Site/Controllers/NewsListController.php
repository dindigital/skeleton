<?php

namespace Site\Controllers;

use Site\Models as models;
use Din\Http\Get;
use Din\Http\Header;

/**
 *
 * @package app.controllers
 */
class NewsListController extends AbstractSiteController
{

    public function get ()
    {


        $cache_name = Header::getUrl();
        $html = $this->_cache->get($cache_name);

        if ( is_null($html) || !CACHE_HTML ) {

            $model = new models\NewsListModel;
            $data = $model->getPage(Get::text('pag'), 10);

            $html = $this->_twig->render('news_list.html', $data);

            if ( CACHE_HTML )
                $this->_cache->save($cache_name, $html, 5 * 60);
        }

        return $html;

    }

}

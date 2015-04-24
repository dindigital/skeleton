<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class IndexController extends AbstractSiteController
{

    public function get ()
    {

        $cache_name = 'index';
        $html = $this->_cache->get($cache_name);

        if ( is_null($html) || !CACHE_HTML ) {

            //$model = new models\IndexModel;
            $data = array();
            //$data = $model->getPage();
            $html = $this->_twig->render('index.html', $data);

            if ( CACHE_HTML )
                $this->_cache->save($cache_name, $html, 5 * 60);
        }

        return $html;

    }

}

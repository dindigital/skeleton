<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class Error404Controller extends AbstractSiteController
{

    public function get ()
    {

        \Din\Http\Header::set404();

        $model = new models\Error404Model;
        $data = $model->getPage(404);

        echo $this->_twig->render('error404.html', $data);

    }

}

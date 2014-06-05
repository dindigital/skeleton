<?php

namespace Site\Controllers;

use Site\Models as models;

/**
 *
 * @package app.controllers
 */
class IndexController extends BaseControllerSite
{

  public function setCustomAssets ( $assetsRead )
  {
    $assetsRead->setGroup('css', array('css_siteindex'));

  }

  public function get ()
  {

    $model = new models\IndexModel;
    $this->_data = $model->getIndex();

    /**
     * Define template e exibição
     */
    $this->setBasicTemplate();
    $this->_view->addFile('src/app/Site/Views/index.phtml', '{$CONTENT}');
    $this->display_html();

  }

}

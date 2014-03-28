<?php

namespace src\app\site\controllers;

use Din\Mvc\Controller\BaseController;
use Din\AssetRead\AssetRead;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerSite extends BaseController
{

  public function __construct ()
  {
    parent::__construct();
  }

  /**
   * Seta os assets
   */
  protected function setAssetsData ()
  {
    $assetRead = new AssetRead('config/assets_site.php');
    $assetRead->setMode(ASSETS);
    $assetRead->setReplace(PATH_REPLACE);
    $assetRead->setGroup('css', array('base'));
    $assetRead->setGroup('js', array('jquery', 'base'));
    $this->_data['assets'] = $assetRead->getAssets();
  }

  /**
   * Seta os arquivos que compõem o layout do adm
   */
  protected function setBasicTemplate ()
  {
    $this->setAssetsData();
    $this->_view->addFile('src/app/site/views/layouts/layout.phtml');
    $this->_view->addFile('src/app/site/views/includes/header.phtml', '{$HEADER}');
    $this->_view->addFile('src/app/site/views/includes/footer.phtml', '{$FOOTER}');
  }

}

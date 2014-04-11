<?php

namespace src\app\site\controllers;

use Din\Mvc\Controller\BaseController;
use src\app\site\models as models;
use Din\Assets\AssetsConfig;
use Din\Assets\AssetsRead;
use Din\Cache\ViewCache;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerSite extends BaseController
{

  protected $_viewcache;

  public function __construct ()
  {
    parent::__construct();
    $this->_viewcache = new ViewCache(CACHE_HTML, CACHE_PATH);
  }

  /**
   * Seta os assets
   */
  protected function setAssetsData ()
  {
    $config = new AssetsConfig('config/assets.php');
    $assetsRead = new AssetsRead($config);
    $assetsRead->setMode(ASSETS);
    $assetsRead->setReplace(PATH_REPLACE);
    $assetsRead->setGroup('css', array('site'));
    $assetsRead->setGroup('js', array('jquery', 'site'));
    $this->_data['assets'] = $assetsRead->getAssets();
  }

  /**
   * Seta os arquivos que compõem o layout do site
   */
  protected function setBasicTemplate ()
  {
    $this->setAssetsData();
    $this->setSettings();
    $this->setNav();
    $this->_view->addFile('src/app/site/views/layouts/layout.phtml');
    $this->_view->addFile('src/app/site/views/includes/header.phtml', '{$HEADER}');
    $this->_view->addFile('src/app/site/views/includes/footer.phtml', '{$FOOTER}');
  }

  /**
   * Pega as configurações
   */
  protected function setSettings ()
  {
    $settingsModels = new models\SettingsModel;
    $this->_data['settings'] = $settingsModels->getSettings();
  }

  /**
   * Pega o menu
   */
  protected function setNav ()
  {
    $pageCatModel = new models\PageCatModel;
    $this->_data['nav'] = $pageCatModel->getNav();
  }

}

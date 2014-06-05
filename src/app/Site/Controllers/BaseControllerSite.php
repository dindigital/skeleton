<?php

namespace Site\Controllers;

use Din\Mvc\Controller\BaseController;
use Din\Assets\AssetsConfig;
use Din\Assets\AssetsRead;
use Site\Helpers\CacheModel;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Din\Cache\Cache;

/**
 * Classe abstrata que será a base de todos os controllers do adm
 */
abstract class BaseControllerSite extends BaseController
{

  protected $_cache;

  public function __construct ()
  {
    parent::__construct();
    $this->_cache = new CacheModel(CACHE_HTML);
    if ( defined('CACHE_PATH') && is_dir(CACHE_PATH) ) {
      $this->_cache->setCacheDriver(new FilesystemCache(CACHE_PATH));
    } else if ( defined('CACHE_MEMCACHE') && defined('CACHE_MEMCACHE_PORT') && CACHE_MEMCACHE ) {
      $this->_cache->setCacheDriver(new MemcacheCache());
      $this->_cache->setMemcache(CACHE_MEMCACHE, CACHE_MEMCACHE_PORT);
    }

  }

  public function setCustomAssets ( $assetsRead )
  {
    //

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
    $assetsRead->setGroup('css', array('css_site'));
    $assetsRead->setGroup('js', array('js_modernizr', 'js_site'));
    $this->setCustomAssets($assetsRead);
    $assets = $assetsRead->getAssets();

    $css = '';
    foreach ( $assets['css'] as $row ) {
      $css .= $row;
    }

    $js = '';
    foreach ( $assets['js'] as $row ) {
      $js .= $row;
    }

    $this->_data['assets']['css'] = $css;
    $this->_data['assets']['js'] = $js;

  }

  /**
   * Seta os arquivos que compõem o layout do site
   */
  protected function setBasicTemplate ()
  {
    $this->setAssetsData();
    $this->_view->addFile('src/app/Site/Views/layouts/layout.phtml');
    $this->_view->addFile('src/app/Site/Views/includes/header.phtml', '{$HEADER}');
    $this->_view->addFile('src/app/Site/Views/includes/footer.phtml', '{$FOOTER}');

  }

}

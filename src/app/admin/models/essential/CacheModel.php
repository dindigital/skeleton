<?php

namespace src\app\admin\models\essential;

use Din\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;

/**
 *
 * @package app.models
 */
class CacheModel
{

  protected $_cache;

  public function __construct ()
  {
    $this->_cache = new Cache();
    if ( defined('CACHE_PATH') && is_dir(CACHE_PATH) ) {
      $this->_cache->setCacheDriver(new FilesystemCache(CACHE_PATH));
    } else if ( defined('CACHE_MEMCACHE') && defined('CACHE_MEMCACHE_PORT') && CACHE_MEMCACHE ) {
      $this->_cache->setCacheDriver(new MemcacheCache());
      $this->_cache->setMemcache(CACHE_MEMCACHE, CACHE_MEMCACHE_PORT);
    }

  }

  public function clear ( $input )
  {

    if ( $input['all'] == 1 ) {
      $this->_cache->deleteAll();
    }

    if ( $input['home'] == 1 ) {
      $this->_cache->delete('index');
    }

    $arrUri = explode(',', $input['uri']);
    foreach ( $arrUri as $uri ) {
      $this->delete($uri);
    }

  }

  public function delete ( $key )
  {
    $this->_cache->delete($key);

  }

}

<?php

namespace src\app\admin\models\essential;

use Din\Cache\ViewCache;

/**
 *
 * @package app.models
 */
class CacheModel
{
    
   protected $_cache;


  public function __construct() {
      $this->_cache = new ViewCache(CACHE_HTML, CACHE_PATH);
  }

  public function clear ( $input )
  {
            
      if ($input['all'] == 1) {
        $this->_cache->deleteAll();
      }
      
      if ($input['home'] == 1) {
        $this->_cache->delete('index');
      }
      
      $arrUri = explode(',', $input['uri']);
      foreach ($arrUri as $uri) {
        $this->delete($uri);
      }
     
  }
  
  public function delete($key) {
    $this->_cache->delete($key);
  }

}

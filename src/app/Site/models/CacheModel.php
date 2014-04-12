<?php

namespace Site\Models;

use Din\Cache\Cache;
use Site\Models\BaseModelSite;

class CacheModel
{

  protected $_model;
  protected $_cache;
  protected $_time;

  public function __construct ( BaseModelSite $model, Cache $cache, $time = 60 )
  {
    $this->_model = $model;
    $this->_cache = $cache;
    $this->_time = $time;

  }

  protected function getCacheName ( $name, $arguments )
  {
    $arguments_json = json_encode($arguments);
    $long_name = get_class($this->_model) . $name . $arguments_json;
    $md5_name = md5($long_name);

    return $md5_name;

  }

  public function __call ( $name, $arguments )
  {
    $cache_name = $this->getCacheName($name, $arguments);

    $result = $this->_cache->get($cache_name);

    if ( is_null($result) ) {
      $result = call_user_func_array(array($this->_model, $name), $arguments);
      $this->_cache->save($cache_name, $result, $this->_time);
    }

    return $result;

  }

}

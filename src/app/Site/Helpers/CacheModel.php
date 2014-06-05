<?php

namespace Site\Helpers;

use Din\Cache\Cache;

class CacheModel extends Cache
{

  protected $_activated;

  public function __construct ( $activated )
  {
    $this->_activated = $activated;

  }

  public function get ( $name )
  {
    if ( $this->_activated )
      return parent::get($name);
    else
      return null;

  }

  public function save ( $name, $html, $time = 60 )
  {
    if ( $this->_activated )
      return parent::save($name, $html, $time);
    else
      return null;

  }

}

<?php

namespace Site\Controllers;

use Twig_Loader_Filesystem;
use Twig_Environment;
use Din\Cache\Cache;
use Doctrine\Common\Cache\FilesystemCache;

abstract class AbstractSiteController implements \Respect\Rest\Routable
{

    protected $_twig;
    protected $_cache;

    public function __construct()
    {
        $loader = new Twig_Loader_Filesystem('src/app/Site/Views');
        $this->_twig = new Twig_Environment($loader);
        $this->_cache = new Cache;
        $this->_cache->setCacheDriver(new FilesystemCache(CACHE_PATH));
    }

}

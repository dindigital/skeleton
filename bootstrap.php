<?php

$init = microtime(true);

require_once 'vendor/autoload.php';

use Din\Router\Router;
use Din\Mvc\Controller\FrontController;
use Din\Config\Config;

require_once 'config/init.php';

try {

  $config = new Config(array(
      'config/config.local.php',
      'config/config.global.php',
  ));
  $config->define();

  $router = new Router('config/routes.php');
  $router->route();

  $fc = new FrontController($router);

  $fc->dispatch();
} catch (Exception $e) {
  die('<h1>' . $e->getMessage() . '</h1>');
}

//echo microtime(true) - $init;

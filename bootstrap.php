<?php

ob_start();

function errHandle ( $errNo, $errStr, $errFile, $errLine )
{
  if ( error_reporting() == 0 ) {
    // @ suppression used, don't worry about it
    return;
  }

  $msg = "$errStr in $errFile on line $errLine";
  throw new ErrorException($msg, $errNo);
}

set_error_handler('errHandle');

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

  $router = new Router();
  $router->setRoutesFile('config/essential_routes.php');
  $router->setRoutesFile('config/routes.php');
  $router->route();

  $fc = new FrontController($router);

  $fc->dispatch();
} catch (Exception $e) {
  ob_clean();
  $erro = new \src\app\admin\controllers\essential\Erro500Controller;
  $erro->get_display($e->getMessage());
}

ob_end_flush();

<?php

function errHandle ( $errNo, $errStr, $errFile, $errLine )
{
  if ( error_reporting() == 0 ) {
    // @ suppression used, don't worry about it
    return;
  }
  $msg = "$errStr in $errFile on line $errLine";
  if ( $errNo == E_NOTICE || $errNo == E_WARNING ) {
    throw new ErrorException($msg, $errNo);
  } else {
    echo $msg;
  }
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

  $router = new Router('config/routes.php');
  $router->route();

  $fc = new FrontController($router);

  $fc->dispatch();
} catch (Exception $e) {
  $erro = new \src\app\admin\controllers\essential\Erro500Controller;
  $erro->get_display($e->getMessage());
}

//echo microtime(true) - $init;

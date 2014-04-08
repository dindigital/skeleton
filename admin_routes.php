<?php

$tcn = (function($controller) {
  $classname = str_replace('_', ' ', $controller);
  $classname = ucwords($classname);
  $classname = str_replace(' ', '', $classname);
  $classname .= 'Controller';
  $classname = 'src\app\admin\controllers\\' . $classname;

  try {
    $ref = new ReflectionClass($classname);
  } catch (Exception $e) {
    $e404 = new src\app\admin\controllers\Error404Controller;
    $e404->get();
    exit;
  }

  return $classname;
});

use Respect\Rest\Router;

$r->get('/admin/*/list/', function($controller) use ($tcn) {
  $r = new Router;
  $r->get('/admin/*/list/', $tcn($controller));
  return $r->run();
});

$r->any('/admin/*/save/*/', function($controller, $id = null) use ($tcn) {
  $r = new Router;
  $r->any('/admin/*/save/*/', $tcn($controller . '_save'), array($id));
  return $r->run();
});

$r->any('/admin/*/', function($controller, $id = null) use ($tcn) {
  $r = new Router;
  $r->any('/admin/*/', $tcn($controller), array($id));
  return $r->run();
});

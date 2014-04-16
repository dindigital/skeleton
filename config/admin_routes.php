<?php

$tcn = (function($controller) {
  $classname = str_replace('_', ' ', $controller);
  $classname = ucwords($classname);
  $classname = str_replace(' ', '', $classname);
  $classname .= 'Controller';
  $classname = 'Admin\Controllers\\' . $classname;

  try {
    $ref = new ReflectionClass($classname);
  } catch (Exception $e) {
    $e404 = new \Admin\Controllers\Error404Controller;
    $e404->get();
    exit;
  }

  return $classname;
});

$r->any('/admin/', 'Admin\Controllers\AdminAuthController');

$r->get('/admin/*/list/', function($controller) use ($r, $tcn) {
  return $r->get("/admin/{$controller}/list/", $tcn($controller));
});

$r->any('/admin/*/save/*/', function($controller, $id = null) use ($r, $tcn) {
  return $r->any("/admin/{$controller}/save/{$id}/", $tcn($controller . '_save'), array($id));
});

$r->any('/admin/*/', function($controller, $id = null) use ($r, $tcn) {
  return $r->any("/admin/{$controller}/", $tcn($controller), array($id));
});

$r->any('/admin/*/delete/', function($controller) use ($r, $tcn) {
  return $r->any("/admin/{$controller}/delete/", $tcn('delete'), array($controller));
});

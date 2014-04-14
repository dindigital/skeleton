<?php

require_once 'config/init.php';
require_once 'vendor/autoload.php';

try {

  $c = new Respect\Config\Container();
  $c->loadFile('config/routes.ini');
  $c->loadFile('config/di.ini');

  $c->dinconfig->define();

  $r = $c->router;

  // _# Rotas mágicas do painel adm
  include 'config/admin_routes.php';

  // _# Erro 404
  $r->any('/**', 'Admin\Controllers\Error404Controller');

  die($r->run());
} catch (Exception $e) {
  $erro = new Admin\Controllers\Erro500Controller;
  $erro->get($e->getMessage());
}
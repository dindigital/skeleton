<?php

require_once 'vendor/autoload.php';
require_once 'config/init.php';

try {

  $c = new Respect\Config\Container();
  $c->loadFile('config/routes.ini');
  $c->loadFile('config/config.ini');

  $c->dinconfig->define();

  $r = $c->router;

  // _# Rotas mágicas do painel adm
  include 'admin_routes.php';

  // _# Erro 404
  $r->any('/**', 'src\app\admin\controllers\Error404Controller');

  die($r->run());
} catch (Exception $e) {
  $erro = new \src\app\admin\controllers\Erro500Controller;
  $erro->get($e->getMessage());
}
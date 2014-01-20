<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        'admin/?$' => array(
            'controller' => 'admin\controllers\essential\UsuarioAuthController',
            'method' => 'index'
        ),
        'admin/index/index/' => array(
            'controller' => 'admin\controllers\essential\IndexController',
            'method' => 'index',
        ),
        'admin/lixeira/lista/' => array(
            'controller' => 'admin\controllers\essential\LixeiraController',
            'method' => 'lista',
        ),
        'admin/lixeira/restaurar/' => array(
            'controller' => 'admin\controllers\essential\LixeiraController',
            'method' => 'restaurar',
        ),
        'admin/lixeira/excluir/' => array(
            'controller' => 'admin\controllers\essential\LixeiraController',
            'method' => 'excluir',
        ),
        'admin/log/list/' => array(
            'controller' => 'admin\controllers\essential\LogController',
            'method' => 'list',
        ),
        'admin/log/cadastro/([0-9]+)/' => array(
            'controller' => 'admin\controllers\essential\LogController',
            'method' => 'save',
        ),
        'admin/config/cadastro/' => array(
            'controller' => 'admin\controllers\essential\ConfigController',
            'method' => 'cadastro',
        ),
        'admin/usuario_auth/logout/' => array(
            'controller' => 'admin\controllers\essential\UsuarioAuthController',
            'method' => 'logout',
        ),
        'admin/usuario_senha/recuperar_senha/' => array(
            'controller' => 'admin\controllers\essential\UsuarioSenhaController',
            'method' => 'recuperar_senha',
        ),
        'admin/usuario_senha/update/([0-9]+)/' => array(
            'controller' => 'admin\controllers\essential\UsuarioSenhaController',
            'method' => 'update',
        ),
        'admin/(.*)' => array(
            'path' => 'admin\controllers\\',
            'type' => RouteTypes::ACTION,
        ),
        '' => array(
            'controller' => 'admin\controllers\essential\Erro404Controller',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

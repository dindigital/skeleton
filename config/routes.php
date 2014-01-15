<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        'admin/?$' => array(
            'controller' => 'admin\controllers\UsuarioAuthController',
            'method' => 'index'
        ),
        'admin/(.*)' => array(
            'path' => 'admin\controllers\\',
            'type' => RouteTypes::ACTION,
        ),
        '' => array(
            'controller' => 'admin\controllers\Erro404Controller',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

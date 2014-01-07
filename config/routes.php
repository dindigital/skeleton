<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        'adm/?$' => array(
            'app' => 'adm',
            'controller' => 'usuarioAuth',
            'method' => 'index'
        ),
        'adm/(.*)' => array(
            'app' => 'adm',
            'type' => RouteTypes::ACTION,
        ),
        'admin/?$' => array(
            'app' => 'admin',
            'controller' => 'usuarioAuth',
            'method' => 'index'
        ),
        'admin/(.*)' => array(
            'app' => 'admin',
            'type' => RouteTypes::ACTION,
        ),
        '' => array(
            'app' => 'adm',
            'controller' => 'Erro404',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

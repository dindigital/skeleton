<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
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
            'app' => 'admin',
            'controller' => 'Erro404',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

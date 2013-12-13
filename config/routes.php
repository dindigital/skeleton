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
    )
);

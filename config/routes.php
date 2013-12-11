<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        '^/?$' => array(
            'app' => 'teste',
            'controller' => 'Index',
            'method' => 'index'
        ),
        '^/inserts/?$' => array(
            'app' => 'teste',
            'controller' => 'Agenda',
            'method' => 'inserts'
        ),
        '^/delete/?$' => array(
            'app' => 'teste',
            'controller' => 'Agenda',
            'method' => 'delete'
        ),
        '^/update/?$' => array(
            'app' => 'teste',
            'controller' => 'Agenda',
            'method' => 'update'
        ),
        '^/select/?$' => array(
            'app' => 'teste',
            'controller' => 'Agenda',
            'method' => 'select'
        ),
        '' => array(
            'app' => 'teste',
            'controller' => 'Index',
            'method' => 'erro',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

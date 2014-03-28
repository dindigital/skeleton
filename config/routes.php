<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        '' => array(
            'controller' => 'site\controllers\IndexController',
            'method' => 'index',
        ),
        'erro404' => array(
            'controller' => 'admin\controllers\essential\Error404Controller',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

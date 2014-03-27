<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        '' => array(
            'controller' => 'admin\controllers\essential\Error404Controller',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

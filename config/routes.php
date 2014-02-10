<?php

use Din\Router\RouteTypes;

return array(
    '' => array(
        'admin/?$' => array(
            'controller' => 'admin\controllers\essential\AdminAuthController',
            'method' => 'index'
        ),
        'admin/index/index/' => array(
            'controller' => 'admin\controllers\essential\IndexController',
            'method' => 'index',
        ),
        'admin/trash/list/' => array(
            'controller' => 'admin\controllers\essential\TrashController',
            'method' => 'list',
        ),
        'admin/trash/restore/' => array(
            'controller' => 'admin\controllers\essential\TrashController',
            'method' => 'restore',
        ),
        'admin/trash/delete/' => array(
            'controller' => 'admin\controllers\essential\TrashController',
            'method' => 'delete',
        ),
        'admin/log/list/' => array(
            'controller' => 'admin\controllers\essential\LogController',
            'method' => 'list',
        ),
        'admin/log/save/([0-9]+)/' => array(
            'controller' => 'admin\controllers\essential\LogController',
            'method' => 'save',
        ),
        'admin/config/save/' => array(
            'controller' => 'admin\controllers\essential\ConfigController',
            'method' => 'save',
        ),
        'admin/admin_auth/logout/' => array(
            'controller' => 'admin\controllers\essential\AdminAuthController',
            'method' => 'logout',
        ),
        'admin/admin_password/recover_password/' => array(
            'controller' => 'admin\controllers\essential\AdminPasswordController',
            'method' => 'recover_password',
        ),
        'admin/admin_password/update/([0-9]+)/' => array(
            'controller' => 'admin\controllers\essential\AdminPasswordController',
            'method' => 'update',
        ),
        'admin/relashionship/ajax/' => array(
            'controller' => 'admin\controllers\essential\RelashionshipController',
            'method' => 'ajax',
        ),
        'admin/(.*)' => array(
            'path' => 'admin\controllers\\',
            'type' => RouteTypes::ACTION,
        ),
        '' => array(
            'controller' => 'admin\controllers\essential\Error404Controller',
            'method' => 'display',
            'type' => RouteTypes::ERR_404,
        ),
    )
);

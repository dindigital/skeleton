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
        'admin/admin_password/update/([a-zA-Z0-9_-]+)/' => array(
            'controller' => 'admin\controllers\essential\AdminPasswordController',
            'method' => 'update',
        ),
        'admin/relashionship/ajax/' => array(
            'controller' => 'admin\controllers\essential\RelashionshipController',
            'method' => 'ajax',
        ),
        'admin/sequence/change/' => array(
            'controller' => 'admin\controllers\essential\SequenceController',
            'method' => 'change',
        ),
        'admin/active/change/' => array(
            'controller' => 'admin\controllers\essential\ActiveController',
            'method' => 'change',
        ),
        'admin/tweet/edit/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/' => array(
            'controller' => 'admin\controllers\essential\TweetController',
            'method' => 'edit',
        ),
        'admin/tweet/view/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/' => array(
            'controller' => 'admin\controllers\essential\TweetController',
            'method' => 'view',
        ),
        'admin/soundcloud/auth/' => array(
            'controller' => 'admin\controllers\essential\SoundCloudController',
            'method' => 'auth',
        ),
        'admin/youtube/auth/' => array(
            'controller' => 'admin\controllers\essential\YouTubeController',
            'method' => 'auth',
        ),
        'admin/facepost/edit/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/' => array(
            'controller' => 'admin\controllers\essential\FacepostController',
            'method' => 'edit',
        ),
        'admin/facepost/view/([a-zA-Z0-9_-]+)/([a-zA-Z0-9_-]+)/' => array(
            'controller' => 'admin\controllers\essential\FacepostController',
            'method' => 'view',
        ),
        'admin/(.*)' => array(
            'path' => 'admin\controllers\\',
            'type' => RouteTypes::ACTION,
        ),
    )
);

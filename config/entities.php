<?php

return array(
    'trash' => array(
        'name' => 'Trash',
        'section' => 'Lixeira',
    ),
    'log' => array(
        'name' => 'Log',
        'section' => 'Log',
    ),
    'settings' => array(
        'tbl' => 'settings',
        'name' => 'Settings',
        'section' => 'Configuração',
        'id' => 'id_settings',
    ),
    'photo' => array(
        'tbl' => 'photo',
        'name' => 'Photo',
        'section' => 'Galeria de Fotos',
        'id' => 'id_photo',
        'title' => 'title',
        'trash' => true,
    ),
    'photo_item' => array(
        'tbl' => 'photo_item',
        'name' => 'PhotoItem',
        'id' => 'id_photo_item',
    ),
    'news' => array(
        'tbl' => 'news',
        'name' => 'News',
        'section' => 'Notícias',
        'id' => 'id_news',
        'title' => 'title',
        'parent' => 'news_cat',
        'trash' => true,
        'sequence' => array(
            'optional' => true,
        )
    ),
    'news_cat' => array(
        'tbl' => 'news_cat',
        'name' => 'NewsCat',
        'section' => 'Categoria de Notícias',
        'id' => 'id_news_cat',
        'title' => 'title',
        'children' => array(
            'news'
        ),
        'trash' => true,
        'sequence' => array(
            'optional' => false,
            'dependence' => 'is_home'
        ),
    ),
    'admin' => array(
        'tbl' => 'admin',
        'name' => 'Admin',
        'section' => 'Usuários',
        'id' => 'id_admin',
        'title' => 'name',
        'trash' => false,
    ),
    'video' => array(
        'tbl' => 'video',
        'name' => 'Video',
        'section' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'title',
        'trash' => true,
    ),
    'page_cat' => array(
        'tbl' => 'page_cat',
        'name' => 'PageCat',
        'section' => 'Menu',
        'id' => 'id_page_cat',
        'title' => 'title',
        'trash' => true,
        'sequence' => array(
            'optional' => false
        )
    ),
    'page' => array(
        'tbl' => 'page',
        'name' => 'Page',
        'section' => 'Página',
        'id' => 'id_page',
        'title' => 'title',
        'trash' => true,
        'sequence' => array(
            'optional' => true,
            'dependence' => 'id_page_cat'
        )
    ),
);

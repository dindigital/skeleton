<?php

return array(
    'Lixeira' => array(
        'tbl' => 'trash',
        'index' => 'list',
    ),
    'Log' => array(
        'tbl' => 'log',
        'index' => 'list',
    ),
    'Configurações' => array(
        'tbl' => 'settings',
        'index' => 'save',
    ),
    'Credenciais de Mídias' => array(
        'tbl' => 'socialmedia_credentials',
        'index' => 'save',
    ),
    'Usuários' => array(
        'tbl' => 'admin',
        'index' => 'list',
    ),
    'Notícias' => array(
        'submenu' => array(
            'Categorias' => array(
                'tbl' => 'news_cat',
                'index' => 'list',
            ),
            'Notícias' => array(
                'tbl' => 'news',
                'index' => 'list',
            ),
        )
    ),
    'Páginas Institucionais' => array(
        'submenu' => array(
            'Menu' => array(
                'tbl' => 'page_cat',
                'index' => 'list',
            ),
            'Página' => array(
                'tbl' => 'page',
                'index' => 'list',
            ),
        )
    ),
    'Galeria de Fotos' => array(
        'tbl' => 'photo',
        'index' => 'list',
    ),
    'Galeria de Vídeos' => array(
        'tbl' => 'video',
        'index' => 'list',
    ),
    'Galeria de Audio' => array(
        'name' => 'Audio',
        'index' => 'list',
    ),
    'Publicações' => array(
        'tbl' => 'publication',
        'index' => 'list',
    ),
    'Tags' => array(
        'tbl' => 'tag',
        'index' => 'list',
    ),
    'Pesquisa de Satisfação' => array(
        'tbl' => 'survey',
        'index' => 'list',
    ),
    'Enquetes' => array(
        'tbl' => 'poll',
        'index' => 'list',
    ),
    'E-mails' => array(
        'submenu' => array(
            'Grupos' => array(
                'tbl' => 'mailing_group',
                'index' => 'list',
            ),
            'E-mails' => array(
                'tbl' => 'mailing',
                'index' => 'list'
            ),
        )
    ),
    'Clientes' => array(
        'tbl' => 'customer',
        'index' => 'list',
    ),
);

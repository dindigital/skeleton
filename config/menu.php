<?php

return array(
    'Cache' => array(
        'tbl' => 'cache',
        'index' => 'save',
    ),
    'Lixeira' => array(
        'tbl' => 'trash',
    ),
    'Log' => array(
        'tbl' => 'log',
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
    ),
    'Notícias' => array(
        'submenu' => array(
            'Categorias' => array(
                'tbl' => 'news_cat',
            ),
            'Subcategorias' => array(
                'tbl' => 'news_sub',
            ),
            'Notícias' => array(
                'tbl' => 'news',
            ),
        )
    ),
    'Páginas Institucionais' => array(
        'submenu' => array(
            'Menu' => array(
                'tbl' => 'page_cat',
            ),
            'Página' => array(
                'tbl' => 'page',
            ),
        )
    ),
    'Galeria de Fotos' => array(
        'tbl' => 'photo',
    ),
    'Galeria de Vídeos' => array(
        'tbl' => 'video',
    ),
    'Galeria de Audio' => array(
        'tbl' => 'audio',
    ),
    'Publicações' => array(
        'tbl' => 'publication',
    ),
    'Tags' => array(
        'tbl' => 'tag',
    ),
    'Pesquisa de Satisfação' => array(
        'tbl' => 'survey',
    ),
    'Enquetes' => array(
        'tbl' => 'poll',
    ),
    'E-mails' => array(
        'submenu' => array(
            'Grupos' => array(
                'tbl' => 'mailing_group',
            ),
            'E-mails' => array(
                'tbl' => 'mailing',
            ),
        )
    ),
    'Clientes' => array(
        'tbl' => 'customer',
    ),
);

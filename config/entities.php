<?php

return array(
    'trash' => array(
        'name' => 'Trash',
        'section' => 'Lixeira',
        'index' => 'list',
    ),
    'log' => array(
        'name' => 'Log',
        'section' => 'Log',
        'id' => 'id_log',
        'index' => 'list',
    ),
    'settings' => array(
        'tbl' => 'settings',
        'name' => 'Settings',
        'section' => 'Configuração',
        'id' => 'id_settings',
        'index' => 'save',
    ),
    'photo' => array(
        'tbl' => 'photo',
        'name' => 'Photo',
        'section' => 'Galeria de Fotos',
        'id' => 'id_photo',
        'title' => 'title',
        'trash' => true,
        'index' => 'list',
    ),
    'photo_item' => array(
        'tbl' => 'photo_item',
        'name' => 'Gallery',
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
        ),
        'index' => 'list',
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
        ),
        'index' => 'list',
    ),
    'admin' => array(
        'tbl' => 'admin',
        'name' => 'Admin',
        'section' => 'Usuários',
        'id' => 'id_admin',
        'title' => 'name',
        'trash' => false,
        'index' => 'list',
    ),
    'video' => array(
        'tbl' => 'video',
        'name' => 'Video',
        'section' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'title',
        'trash' => true,
        'index' => 'list',
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
        ),
        'children' => array(
            'page'
        ),
        'index' => 'list',
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
        ),
        'parent' => 'page_cat',
        'index' => 'list',
    ),
    'survey' => array(
        'tbl' => 'survey',
        'name' => 'Survey',
        'section' => 'Pesquisa de Satisfação',
        'id' => 'id_survey',
        'title' => 'title',
        'trash' => true,
        'children' => array(
            'survey_question'
        ),
        'index' => 'list',
    ),
    'survey_question' => array(
        'tbl' => 'survey_question',
        'name' => 'SurveyQuestion',
        'id' => 'id_survey_question',
        'title' => 'question',
        'parent' => 'survey',
    ),
    'poll' => array(
        'tbl' => 'poll',
        'name' => 'Poll',
        'section' => 'Enquete',
        'id' => 'id_poll',
        'title' => 'question',
        'trash' => true,
        'children' => array(
            'poll_option'
        ),
        'index' => 'list',
    ),
    'poll_option' => array(
        'tbl' => 'poll_option',
        'name' => 'PollOption',
        'id' => 'id_poll_option',
        'title' => 'option',
        'parent' => 'poll'
    ),
    'tag' => array(
        'tbl' => 'tag',
        'name' => 'Tag',
        'section' => 'Tags',
        'id' => 'id_tag',
        'title' => 'title',
        'trash' => true,
        'index' => 'list',
    ),
    'publication' => array(
        'tbl' => 'publication',
        'name' => 'Publication',
        'section' => 'Publicações',
        'id' => 'id_publication',
        'title' => 'title',
        'trash' => true,
        'index' => 'list',
    ),
);

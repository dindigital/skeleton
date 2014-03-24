<?php

return array(
    'trash' => array(
        'name' => 'Trash',
        'section' => 'Lixeira',
    ),
    'log' => array(
        'name' => 'Log',
        'section' => 'Log',
        'id' => 'id_log',
    ),
    'settings' => array(
        'name' => 'Settings',
        'section' => 'Configuração',
        'id' => 'id_settings',
        'title' => 'title',
    ),
    'socialmedia_credentials' => array(
        'name' => 'SocialmediaCredentials',
        'section' => 'Credenciais de Mídias',
        'id' => 'id_socialmedia_credentials',
        'title' => '',
    ),
    'photo' => array(
        'name' => 'Photo',
        'section' => 'Galeria de Fotos',
        'id' => 'id_photo',
        'title' => 'title',
        'trash' => true,
    ),
    'photo_item' => array(
        'name' => 'PhotoItem',
        'id' => 'id_photo_item',
    ),
    'news' => array(
        'name' => 'News',
        'section' => 'Notícias',
        'id' => 'id_news',
        'title' => 'title',
        'parent' => 'news_cat',
        'trash' => true,
        'sequence' => array(
            'optional' => true,
        ),
    ),
    'news_cat' => array(
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
    ),
    'admin' => array(
        'name' => 'Admin',
        'section' => 'Usuários',
        'id' => 'id_admin',
        'title' => 'name',
        'names' => array('\src\app\admin\models\essential\ConfigModel')
    ),
    'video' => array(
        'name' => 'Video',
        'section' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'title',
        'trash' => true,
    ),
    'page_cat' => array(
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
    ),
    'page' => array(
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
    ),
    'survey' => array(
        'name' => 'Survey',
        'section' => 'Pesquisa de Satisfação',
        'id' => 'id_survey',
        'title' => 'title',
        'trash' => true,
        'children' => array(
            'survey_question'
        ),
    ),
    'survey_question' => array(
        'name' => 'SurveyQuestion',
        'id' => 'id_survey_question',
        'title' => 'question',
        'parent' => 'survey',
    ),
    'poll' => array(
        'name' => 'Poll',
        'section' => 'Enquete',
        'id' => 'id_poll',
        'title' => 'question',
        'trash' => true,
        'children' => array(
            'poll_option'
        ),
    ),
    'poll_option' => array(
        'name' => 'PollOption',
        'id' => 'id_poll_option',
        'title' => 'option',
        'parent' => 'poll'
    ),
    'tag' => array(
        'name' => 'Tag',
        'section' => 'Tags',
        'id' => 'id_tag',
        'title' => 'title',
        'trash' => true,
    ),
    'publication' => array(
        'name' => 'Publication',
        'section' => 'Publicações',
        'id' => 'id_publication',
        'title' => 'title',
        'trash' => true,
    ),
    'audio' => array(
        'name' => 'Audio',
        'section' => 'Audio',
        'id' => 'id_audio',
        'title' => 'title',
        'trash' => true,
    ),
    'mailing_group' => array(
        'name' => 'MailingGroup',
        'section' => 'Grupo de E-mail',
        'id' => 'id_mailing_group',
        'title' => 'name',
    ),
    'mailing' => array(
        'name' => 'Mailing',
        'section' => 'E-mails',
        'id' => 'id_mailing',
        'title' => 'name',
    ),
    'mailing_import' => array(
        'name' => 'MailingImport',
        'section' => 'Importação de E-mail',
    ),
    'mailing_export' => array(
        'name' => 'MailingExport',
        'section' => 'Exportação de E-mail',
    ),
    'customer' => array(
        'name' => 'Customer',
        'section' => 'Clientes',
        'id' => 'id_customer',
        'title' => 'name',
    ),
    'tweet' => array(
        'name' => 'Tweet',
        'section' => 'Tweet',
        'id' => 'id_tweet',
        'title' => 'msg',
    ),
);

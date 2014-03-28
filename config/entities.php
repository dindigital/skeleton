<?php

return array(
    'cache' => array(
        'model' => 'essential\CacheModel',
        'section' => 'Cache',
    ),
    'trash' => array(
        'model' => 'essential\TrashModel',
        'section' => 'Lixeira',
    ),
    'log' => array(
        'model' => 'essential\LogModel',
        'section' => 'Log',
        'id' => 'id_log',
    ),
    'settings' => array(
        'model' => 'SettingsModel',
        'section' => 'Configuração',
        'id' => 'id_settings',
        'title' => 'title',
    ),
    'socialmedia_credentials' => array(
        'model' => 'SocialmediaCredentials',
        'section' => 'Credenciais de Mídias',
        'id' => 'id_socialmedia_credentials',
        'title' => '',
    ),
    'photo' => array(
        'model' => 'PhotoModel',
        'section' => 'Galeria de Fotos',
        'id' => 'id_photo',
        'title' => 'title',
        'trash' => true,
    ),
    'photo_item' => array(
        'model' => 'PhotoItemModel',
        'id' => 'id_photo_item',
    ),
    'news' => array(
        'model' => 'NewsModel',
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
        'model' => 'NewsCatModel',
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
        'model' => 'AdminModel',
        'section' => 'Usuários',
        'id' => 'id_admin',
        'title' => 'name',
    ),
    'video' => array(
        'model' => 'VideoModel',
        'section' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'title',
        'trash' => true,
    ),
    'page_cat' => array(
        'model' => 'PageCatModel',
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
        'model' => 'PageModel',
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
        'model' => 'SurveyModel',
        'section' => 'Pesquisa de Satisfação',
        'id' => 'id_survey',
        'title' => 'title',
        'trash' => true,
        'children' => array(
            'survey_question'
        ),
    ),
    'survey_question' => array(
        'model' => 'SurveyQuestionModel',
        'id' => 'id_survey_question',
        'title' => 'question',
        'parent' => 'survey',
    ),
    'poll' => array(
        'model' => 'PollModel',
        'section' => 'Enquete',
        'id' => 'id_poll',
        'title' => 'question',
        'trash' => true,
        'children' => array(
            'poll_option'
        ),
    ),
    'poll_option' => array(
        'model' => 'PollOptionModel',
        'id' => 'id_poll_option',
        'title' => 'option',
        'parent' => 'poll'
    ),
    'tag' => array(
        'model' => 'TagModel',
        'section' => 'Tags',
        'id' => 'id_tag',
        'title' => 'title',
        'trash' => true,
    ),
    'publication' => array(
        'model' => 'PublicationModel',
        'section' => 'Publicações',
        'id' => 'id_publication',
        'title' => 'title',
        'trash' => true,
    ),
    'audio' => array(
        'model' => 'AudioModel',
        'section' => 'Audio',
        'id' => 'id_audio',
        'title' => 'title',
        'trash' => true,
    ),
    'mailing_group' => array(
        'model' => 'MailingGroupModel',
        'section' => 'Grupo de E-mail',
        'id' => 'id_mailing_group',
        'title' => 'name',
    ),
    'mailing' => array(
        'model' => 'MailingModel',
        'section' => 'E-mails',
        'id' => 'id_mailing',
        'title' => 'name',
    ),
    'mailing_import' => array(
        'model' => 'MailingImportModel',
        'section' => 'Importação de E-mail',
    ),
    'mailing_export' => array(
        'model' => 'MailingExportModel',
        'section' => 'Exportação de E-mail',
    ),
    'customer' => array(
        'model' => 'CustomerModel',
        'section' => 'Clientes',
        'id' => 'id_customer',
        'title' => 'name',
    ),
    'tweet' => array(
        'model' => 'essential\TweetModel',
        'section' => 'Tweet',
        'id' => 'id_tweet',
        'title' => 'msg',
    ),
);

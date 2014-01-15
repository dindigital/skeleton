<?php

return array(
    'configuracao' => array(
        'tbl' => 'configuracao',
        'name' => 'Configuracao',
        'secao' => 'Configuração',
        'id' => 'id_configuracao',
    ),
    'foto' => array(
        'tbl' => 'foto',
        'name' => 'Foto',
        'secao' => 'Fotos',
        'id' => 'id_foto',
        'title' => 'titulo',
        'lixeira' => true,
    ),
    'noticia' => array(
        'tbl' => 'noticia',
        'name' => 'Noticia',
        'secao' => 'Notícias',
        'id' => 'id_noticia',
        'title' => 'titulo',
        'pai' => 'noticia_cat',
        'lixeira' => true,
        'ordem' => array(
            'opcional' => true,
        )
    ),
    'noticia_cat' => array(
        'tbl' => 'noticia_cat',
        'name' => 'NoticiaCat',
        'secao' => 'Categoria de Notícias',
        'id' => 'id_noticia_cat',
        'title' => 'titulo',
        'filho' => array(
            'noticia'
        ),
        'lixeira' => true,
        'ordem' => array(
            'opcional' => false,
            'dependencia' => 'home'
        ),
    ),
    'usuario' => array(
        'tbl' => 'usuario',
        'name' => 'Usuario',
        'secao' => 'Usuários',
        'id' => 'id_usuario',
        'title' => 'nome',
        'lixeira' => false,
    ),
    'video' => array(
        'tbl' => 'video',
        'name' => 'Video',
        'secao' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'titulo',
        'lixeira' => true,
    ),
    'pagina_cat' => array(
        'tbl' => 'pagina_cat',
        'name' => 'PaginaCat',
        'secao' => 'Menu',
        'id' => 'id_pagina_cat',
        'title' => 'titulo',
        'lixeira' => true,
        'ordem' => array(
            'opcional' => false
        )
    ),
    'pagina' => array(
        'tbl' => 'pagina',
        'name' => 'Pagina',
        'secao' => 'Página',
        'id' => 'id_pagina',
        'title' => 'titulo',
        'lixeira' => true,
        'ordem' => array(
            'opcional' => true,
            'dependencia' => 'id_pagina_cat'
        )
    ),
);

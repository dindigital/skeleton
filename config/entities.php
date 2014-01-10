<?php

return array(
    'foto' => array(
        'tbl' => 'foto',
        'model' => 'FotoModel',
        'validator' => 'FotoValidator',
        'secao' => 'Fotos',
        'id' => 'id_foto',
        'title' => 'titulo',
        'lixeira' => true,
    ),
    'noticia' => array(
        'tbl' => 'noticia',
        'model' => 'NoticiaModel',
        'validator' => 'NoticiaValidator',
        'secao' => 'Notícias',
        'id' => 'id_noticia',
        'title' => 'titulo',
        'pai' => 'noticia_cat',
        'lixeira' => true,
    ),
    'noticia_cat' => array(
        'tbl' => 'noticia_cat',
        'model' => 'NoticiaCatModel',
        'validator' => 'NoticiaCatValidator',
        'secao' => 'Categoria de Notícias',
        'id' => 'id_noticia_cat',
        'title' => 'titulo',
        'filho' => array(
            'noticia'
        ),
        'lixeira' => true,
    ),
    'usuario' => array(
        'tbl' => 'usuario',
        'model' => 'UsuarioModel',
        'validator' => 'UsuarioValidator',
        'secao' => 'Usuários',
        'id' => 'id_usuario',
        'title' => 'nome',
        'lixeira' => false,
    ),
    'video' => array(
        'tbl' => 'video',
        'model' => 'VideoModel',
        'validator' => 'VideoValidator',
        'secao' => 'Vídeos',
        'id' => 'id_video',
        'title' => 'titulo',
        'lixeira' => true,
    ),
);

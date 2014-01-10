<?php

return array(
    'foto' => array(
        'tbl' => 'foto',
        'model' => 'FotoModel',
        'validator' => 'FotoValidator',
        'secao' => 'Fotos',
        'id' => 'id_foto',
        'title' => 'titulo',
        'lixeira' => false,
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
);

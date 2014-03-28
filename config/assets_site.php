<?php

/**
 * Exemplo de Uso:
 * Gerando tudo $ php compressor.php
 * Gerando específico $ php5-cgi compressor.php css=adm_login,adm js=adm_login
 */
return array(
    'css' => array(
        'base' => array(
            'uri' => '/assets/base.css',
            'src' => array(
                'public/site/css/bootstrap/bootstrap.css',
                'public/site/css/base.css',
                'public/site/css/bootstrap/bootstrap-responsive.css',
            )
        ),
    ),
    'js' => array(
        'jquery' => array(
            'uri' => '/assets/jquery.js',
            'src' => array(
                'public/admin/js/jquery-1.9.1.js',
            )
        ),
        'base' => array(
            'uri' => '/assets/base.js',
            'src' => array(
                'public/site/js/base.js',
            )
        ),
    )
);

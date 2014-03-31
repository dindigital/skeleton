<?php

/**
 * Exemplo de Uso:
 * Gerando tudo $ php compressor.php
 * Gerando específico $ php5-cgi compressor.php css=adm_login,adm js=adm_login
 */
return array(
    'css' => array(
        'site' => array(
            'uri' => '/assets/site533943325a11f.css',
            'src' => array(
                'public/site/vendor/bootstrap/css/bootstrap.css',
                'public/site/vendor/bootstrap/css/bootstrap-theme.css',
                'public/site/css/base.css',
            )
        ),
        'adm_login' => array(
            'uri' => '/assets/adm_login53394333bab77.css',
            'src' => array(
                'public/admin/js/bootstrap/dist/css/bootstrap.css',
                'public/admin/fonts/font-awesome-4/css/font-awesome.min.css',
                'public/admin/css/style.css',
            )
        ),
        'adm' => array(
            'uri' => '/assets/adm533943359d9a9.css',
            'src' => array(
                'public/admin/css/jquery-ui.css',
                'public/admin/js/bootstrap/dist/css/bootstrap.css',
                'public/admin/fonts/font-awesome-4/css/font-awesome.min.css',
                'public/admin/js/jquery.select2/select2.css',
                'public/admin/js/plupload/js/jquery.plupload.queue/css/jquery.plupload.queue.css',
                'public/admin/js/spectrum/spectrum.css',
                'public/admin/css/style.css',
            )
        ),
        'google' => array(
            'uri' => '/assets/google533943392f8dc.css',
            'src' => array(
                'public/admin/css/google_open_sans.css',
                'public/admin/css/google_raleway.css',
            )
        ),
    ),
    'js' => array(
        'site' => array(
            'uri' => '/assets/site53394339ab27f.js',
            'src' => array(
                'public/site/vendor/bootstrap/js/bootstrap.js',
                'public/site/js/base.js',
            )
        ),
        'jquery' => array(
            'uri' => '/assets/jquery5339433ab0eb7.js',
            'src' => array(
                'public/admin/js/jquery-1.9.1.js',
                'public/admin/js/jquery-ui.js',
            )
        ),
        'adm_login' => array(
            'uri' => '/assets/adm_login5339433da4319.js',
            'src' => array(
                'public/admin/js/ajaxform/jquery.form.js',
                'public/admin/js/base.js',
                'public/admin/js/ajaxform.js',
                'public/admin/js/bootstrap/dist/js/bootstrap.min.js',
            )
        ),
        'adm' => array(
            'uri' => '/assets/adm533943403d2b3.js',
            'src' => array(
                'public/admin/js/plupload/js/plupload.full.js',
                'public/admin/js/plupload/js/jquery.plupload.queue/jquery.plupload.queue.js',
                'public/admin/js/ajaxform/jquery.form.js',
                'public/admin/js/jquery.select2/select2.min.js',
                'public/admin/js/select2.js',
                'public/admin/js/spectrum/spectrum.js',
                'public/admin/js/daterangepicker/date.js',
                'public/admin/js/daterangepicker/daterangepicker.js',
                'public/admin/js/jquery.maskedinput/jquery.maskedinput.js',
                'public/admin/js/counter/jquery.textareaCounter.plugin.js',
                'public/admin/js/maskMoney/jquery.maskMoney.js',
                'public/admin/js/postCodeAjax.js',
                'public/admin/js/base.js',
                'public/admin/js/prefix.js',
                'public/admin/js/form.js',
                'public/admin/js/ajaxform.js',
                'public/admin/js/list.js',
                'public/admin/js/bootstrap/dist/js/bootstrap.min.js',
                'public/admin/js/scripts.js',
            )
        ),
    )
);

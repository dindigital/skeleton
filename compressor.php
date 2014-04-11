<?php

/**
 * Exemplo de Uso:
 * Gerando tudo $ php compressor.php
 * Gerando específico $ php5-cgi compressor.php g=site,adm
 */
chdir(getcwd() . '/');
require_once 'vendor/autoload.php';

use Din\Assets\AssetsConfig;
use Din\Assets\Compressor\CompressorCreator;
use Din\Assets\Compressor\Js;
use Din\Assets\Compressor\Css;
use Din\Http\Get;

$group = array();
$g = Get::text('g');
if ( $g ) {
  $group = explode(',', $g);
}

$config = new AssetsConfig('config/assets.php');
$compressor = new CompressorCreator($config, $group);
$compressor->factoryMethod(new Js());
$compressor->factoryMethod(new Css());

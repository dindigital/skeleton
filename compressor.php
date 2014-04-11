<?php

/**
 * Exemplo de Uso:
 * Gerando tudo $ php compressor.php
 * Gerando específico $ php5-cgi compressor.php g=site,adm
 */
chdir(getcwd() . '/');
require_once 'vendor/autoload.php';

use Din\Assets\Compressor\CompressorCreator;
use Din\Assets\Compressor\Js;
use Din\Assets\Compressor\Css;

$group = array();
if ( isset($_GET['g']) && count($_GET['g']) ) {
  $group = explode(',', $_GET['g']);
}

$compressor = new CompressorCreator('config/assets.php', $group);
$compressor->factoryMethod(new Js());
$compressor->factoryMethod(new Css());

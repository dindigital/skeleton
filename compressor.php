<?php

chdir(getcwd() . '/');
require_once 'vendor/autoload.php';

use Din\AssetMin\AssetMin;

$asset = new AssetMin('config/assets.php');

if ( !count($_GET) ) {
  $asset->compressorAll();
} else {
  if ( isset($_GET['css']) && count($_GET['css']) ) {
    $css = explode(',', $_GET['css']);
    $asset->compressor('css', $css);
  }
  if ( isset($_GET['js']) && count($_GET['js']) ) {
    $js = explode(',', $_GET['js']);
    $asset->compressor('js', $js);
  }
}
<?php

chdir('/var/www/framework/');
require_once 'vendor/autoload.php';

use Din\AssetMin\AssetMin;

$asset = new AssetMin('config/assets_read.php');

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
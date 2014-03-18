<?php

require_once '../../vendor/autoload.php';
require_once 'cssmin.php';
require_once 'JSMinPlus.php';

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinPlusFilter;
use Assetic\AssetWriter;
use Assetic\AssetManager;

$pathCSS = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/css/';
$pathJS = $_SERVER['DOCUMENT_ROOT'] . '/public/admin/js/';

$am = new AssetManager();

$js = new AssetCollection(array(
    new FileAsset($pathJS . 'ajaxform.js'),
    new FileAsset($pathJS . 'base.js'),
        ), array(
    new JSMinPlusFilter(),
        ));
$am->set('basejs', $js);

$js->setTargetPath('mariojs.js');

$css = new AssetCollection(array(
    new FileAsset($pathCSS . 'jquery-ui.css'),
    new FileAsset($pathCSS . 'style.css'),
        ), array(
    new CssMinFilter(),
        ));

$am->set('basecss', $css);

$css->setTargetPath('mariojs.css');

$writer = new AssetWriter($_SERVER['DOCUMENT_ROOT'] . '/public/assets');
$writer->writeManagerAssets($am);

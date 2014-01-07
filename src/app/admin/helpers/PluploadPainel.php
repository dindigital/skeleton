<?php

namespace src\app\admin\helpers;

use Din\Form\Upload\iUploadBuilder;
use Din\Form\Upload\Plupload\Plupload;

class PluploadPainel implements iUploadBuilder
{

  /**
   *
   * @param string $field_name
   * @param string $type
   * @param bool $obg
   * @param bool $multiple
   * @return string
   */
  public static function getButton ( $field_name, $type, $obg = false, $multiple = false, $uploader = null )
  {
    if ( is_null($uploader) ) {
      $uploader = '/admin/js/plupload/upload.php';
    }

    $Upl = new Plupload($field_name);
    $class = 'pupload';
    if ( $obg )
      $class .= ' obg';

    $Upl->setClass($class);
    $Upl->setMultiple($multiple);
    $Upl->setType($type);
    $Upl->setOpt('runtimes', "'flash,gears,silverlight,html5,browserplus'");
    $Upl->setOpt('url', "'{$uploader}'");
    $Upl->setOpt('flash_swf_url', "'/admin/js/plupload/js/plupload.flash.swf'");
    $Upl->setOpt('silverlight_xap_url', "'/admin/js/plupload/js/plupload.silverlight.xap'");
    $Upl->setOpt('unique_names ', "true");

    $r = $Upl->getButton();

    return $r;
  }

}

<?php

namespace src\helpers;

use Exception;

class JsonViewHelper
{

  public static function display_error_message ( Exception $e )
  {
    $exceptionMsg = $e->getMessage();
    json_decode($exceptionMsg);
    $msg = (json_last_error() == JSON_ERROR_NONE) ? json_decode($exceptionMsg) : $exceptionMsg;

    if ( is_array($msg) ) {
      $msg = implode('<br />', $msg);
    }

    die(json_encode(array(
        'type' => 'error_message',
        'message' => $msg
    )));

  }

  public static function display_error_object ( Exception $e )
  {
    die(json_encode(array(
        'type' => 'error_object',
        'objects' => json_decode($e->getMessage(), true)
    )));

  }

  public static function redirect ( $uri )
  {
    die(json_encode(array(
        'type' => 'redirect',
        'uri' => $uri
    )));

  }

  public static function display_success_message ( $msg )
  {
    die(json_encode(array(
        'type' => 'success',
        'message' => $msg
    )));

  }

  public static function display ( $mixed )
  {
    die(json_encode($mixed));

  }

}

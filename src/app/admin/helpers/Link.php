<?php

namespace src\app\admin\helpers;

class Link
{

  public static function formatUri ( $uri, $separate_id = true )
  {
    if ( is_null($uri) )
      return null;


    $arrayUri = explode('/', $uri, -1);
    $link = array_pop($arrayUri);
    $arrayLink = explode('-', $link);

    if ( $separate_id ) {
      $id = array_pop($arrayLink);
    } else {
      $id = null;
    }

    $prefix = implode('/', $arrayUri);
    $uri = implode('-', $arrayLink);

    $r = array(
        'prefix' => $prefix,
        'uri' => $uri,
        'id' => $id
    );

    return $r;
  }

  public static function formatNavUri ( $uri, $prefix = null )
  {
    if ( is_null($uri) )
      return null;

    if ( !is_null($prefix) ) {
      $uri = explode('/', $uri);
      $prefix = "/$uri[1]";
      unset($uri[1]);
      $uri = implode('/', $uri);
    }

    $r = array(
        'prefix' => $prefix,
        'uri' => $uri,
        'id' => null
    );

    return $r;
  }

}

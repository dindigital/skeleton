<?php

namespace src\app\admin\helpers;

use Din\Image\Picuri;

class Preview
{

  /**
   * Utilizado para mostrar imagens.
   * Redimensiona a imagem e retorna a string com ela dentro de uma div
   *
   * @param string $uri
   * @return string
   */
  public static function preview ( $uri )
  {
    $ext = strtolower(pathinfo($uri, PATHINFO_EXTENSION));

    switch ($ext) {
      case 'jpg':
      case 'jpeg':
      case 'gif':
      case 'png':
        try {

          $img = Picuri::picUri($uri, 150, 100);
        } catch (\Exception $e) {
          $img = Picuri::picUri('/frontend/images/error404.jpg', 150, 100);
        }
        $r = '<a href="' . $uri . '" target="_blank">' . $img . '</a>';
        break;
      case 'mp3':
        $r = '<br /><a href="' . $uri . '" target="_blank">' . $uri . '</a>';
        break;
      default :
        $r = '<br /><a href="' . $uri . '" target="_blank">' . $uri . '</a>';
    }

    $r = '<div class="preview">' . $r . '</div>';

    return $r;

  }

}

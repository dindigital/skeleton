<?php

namespace src\app\admin\helpers;

use Din\Image\Picuri;

class Galeria
{

  /**
   * Monta a representaçãso das fotos da galeria, com campo pra legenda e crédito
   *
   * @param string $prop_name nome da propriedade que contém as fotos
   */
  public static function get ( $galeria, $prop_name )
  {
    if ( !count($galeria) ) {
      return '';
    }

    $html = '<ul class="gallery sortable" id="' . $prop_name . '_ul">' . PHP_EOL;

    foreach ( $galeria as $item ) {
      $attribs['alt'] = $item['legenda'];
      $attribs['class'] = 'img_galeria';
      $img = Picuri::picUri($item['arquivo'], 196, 150, true, $attribs);

      $html .= '<li id="' . $item['id_foto_item'] . '">' . PHP_EOL;
      $html .= $img . PHP_EOL;
      $html .= '<input placeholder="Legenda" name="legenda[]" type="text" value="' . $item['legenda'] . '" />' . PHP_EOL;
      $html .= '<input placeholder="Crédito" name="credito[]" type="text" value="' . $item['credito'] . '" />' . PHP_EOL;
      $html .= '</li>' . PHP_EOL;
    }

    $html .= '</ul>' . PHP_EOL;
    $html .= '<input type="hidden" name="' . $prop_name . '_ordem" id="' . $prop_name . '_ordem" />' . PHP_EOL;

    //_# SCRIPT PARA ENVIAR A ORDEM DAS FOTOS
    $html .= '<script>' . PHP_EOL;
    $html .= '  $("#' . $prop_name . '_ul").parents("form").submit(function(){' . PHP_EOL;
    $html .= '    $("#' . $prop_name . '_ordem").val($("#' . $prop_name . '_ul").sortable("toArray").toString());' . PHP_EOL;
    $html .= '  });' . PHP_EOL;
    $html .= '</script>' . PHP_EOL;

    //_# QUADRO DE AUXILIO A OPERAÇÕES
    $html .= '<ul class="galleryInfo">' . PHP_EOL;
    $html .= '  <li>Para alterar a ordem da galeria bastanta clicar na foto e arrastar para a posição adequada.</li>' . PHP_EOL;
    $html .= '  <li>Para excluir uma imagem clique duas vezes e então confirmar.</li>' . PHP_EOL;
    $html .= '</ul>' . PHP_EOL;

    return $html;
  }

}

<?php

namespace src\app\adm005\objects;

class Arrays
{

  public static function arrayLogCrud ( $selected = null )
  {
    $array = array(
        'C' => 'Inserir',
        'R' => 'Restaurar da Lixeira',
        'U' => 'Atualizar',
        'D' => 'Excluir',
        'L' => 'Enviar para Lixeira',
        'I' => 'Importar',
            //'Z' => 'Desfazer Importação',
            //'T' => 'Twittar',
    );

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arrayLogSecao ( $selected = null )
  {
    $array = array(
        'Banner' => 'Banners',
        'Cliente' => 'Clientes',
        'Palesstra' => 'Palesstras',
        'PalestraUltima' => 'Últimas Palestras',
        'Depotexto' => 'Depoimento Texto',
        'depovideo' => 'Depoimento Vídeo',
        'MidiaCat' => 'Categoria de Mídia',
        'Midia' => 'Mídia',
        'Artigo' => 'Artigos',
        'Loja' => 'Loja',
    );

    asort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arrayTipoPedido ( $selected = null )
  {
    $array = array(
        'P' => 'Produto',
        'S' => 'Serviço',
    );

    asort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arrayTipoLogin ( $selected = null )
  {
    $array = array(
        'C' => 'Cliente',
        'F' => 'Fornecedor',
    );

    asort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arrayQtdFuncionario ( $selected = null )
  {
    $array = array(
        '1' => '1 - 10',
        '2' => '11 - 100',
        '3' => '101 - 1000',
    );

    ksort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arraySituacaoPedido ( $selected = null )
  {
    $array = array(
        'A' => 'Atendido',
        'E' => 'Em Aberto',
    );

    ksort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arrayOndeConheceu ( $selected = null )
  {
    $array = array(
        '1' => 'Internet',
        '2' => 'Internet',
        '3' => 'Internet',
        '4' => 'Internet',
    );

    ksort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

  public static function arraySegmentacao ( $selected = null )
  {
    $array = array(
        '1' => 'Finanças',
        '2' => 'Finanças',
        '3' => 'Finanças',
        '4' => 'Finanças',
    );

    ksort($array);

    if ( $selected )
      return $array[$selected];

    return $array;
  }

}

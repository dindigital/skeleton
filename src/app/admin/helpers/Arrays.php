<?php

namespace src\app\admin\helpers;

class Arrays
{

  public static $simNao = array(
      '1' => 'Sim',
      '2' => 'Não',
  );

  public static function logAcao ( $acao = null )
  {
    $array = array(
        'C' => 'Cadastrar',
        'U' => 'Atualizar',
        'D' => 'Deletar',
        'T' => 'Enviar para lixeira',
        'R' => 'Restaurar da lixeira'
    );

    if ( $acao && key_exists($acao, $array) ) {
      return $array[$acao];
    }

    return $array;
  }

}

<?php

namespace src\app\admin\formats;

use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Form\Listbox\Listbox;

class UsuarioFormat
{

  public static function formatResult ( $result )
  {
    foreach ( $result as $i => $row ) {
      $result[$i]['inc_data'] = DateFormat::filter_date($row['inc_data']);
    }

    return $result;
  }

  public static function formatRow ( $row, $permissao_listbox )
  {
    if ( !empty($row) ) {
      $row['nome'] = htmlspecialchars($row['nome']);
      $avatar = $row['avatar'];
      $avatar2 = $row['avatar2'];
      $avatar3 = $row['avatar3'];
      $permissoes = json_decode($row['permissao']);
    } else {
      $avatar = '';
      $avatar2 = '';
      $avatar3 = '';
      $permissoes = array();
    }

    $d = new Listbox('permissao');
    $d->setOptionsArray($permissao_listbox);
    $d->setClass('form-control');
    $d->setSelected($permissoes);

    $row['permissao'] = $d->getElement();

    $row['avatar'] = Form::Upload('avatar', $avatar, 'imagem');
    $row['avatar2'] = Form::Upload('avatar2', $avatar2, 'imagem');
    $row['avatar3'] = Form::Upload('avatar3', $avatar3, 'imagem');

    return $row;
  }

}

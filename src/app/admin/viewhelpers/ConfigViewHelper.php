<?php

namespace src\app\admin\viewhelpers;

use src\app\admin\helpers\Form;

class ConfigViewHelper
{

  public static function formatRow ( $row )
  {
    $row['avatar'] = Form::Upload('avatar', $row['avatar'], 'image', false);

    return $row;
  }

}

<?php

namespace src\app\admin\viewhelpers;

use src\app\admin\helpers\Form;

class MailingImportViewHelper
{

  public static function createFields ()
  {
    $row = array(
        'xls' => Form::Upload('xls', '', 'excel')
    );

    return $row;
  }

}

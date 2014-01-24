<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\Form\Listbox\Listbox;
use src\app\admin\helpers\Entities;
use Exception;

/**
 *
 * @package app.models
 */
class PermissaoModel extends BaseModelAdm
{

  public function getListbox ()
  {
    $arrOptions = array();
    foreach ( Entities::$entities as $tbl => $entity ) {
      if ( isset($entity['secao']) ) {
        $arrOptions[$entity['name']] = $entity['secao'];
      }
    }

    return $arrOptions;
  }

  public function block ( $model, $user )
  {
    $permissoes = $this->getArray($user);
    $entity = Entities::getThis($model);

    if ( !in_array($entity['name'], $permissoes) ) {
      throw new Exception('Permissão negada.');
    }
  }

  public function getArray ( $user )
  {
    if ( $user['email'] == 'suporte@dindigital.com' ) {
      $permissoes = array();
      foreach ( Entities::$entities as $tbl => $entity ) {
        $permissoes[] = $entity['name'];
      }
    } else {
      $permissoes = json_decode($user['permission']);
    }

    return $permissoes;
  }

}

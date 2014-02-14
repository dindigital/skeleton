<?php

namespace src\app\admin\models\essential;

use src\app\admin\helpers\Entities;
use Exception;

/**
 *
 * @package app.models
 */
class PermissionModel
{

  public function __construct ()
  {
    Entities::readFile('config/entities.php');
  }

  public function getArrayList ()
  {
    $arrOptions = array();
    foreach ( Entities::$entities as $tbl => $entity ) {
      if ( isset($entity['section']) ) {
        $arrOptions[$entity['name']] = $entity['section'];
      }
    }

    return $arrOptions;
  }

  public function block ( $model, $user )
  {
    $permissoes = $this->getByAdmin($user);
    $entity = Entities::getThis($model);

    if ( !array_key_exists($entity['name'], $permissoes) ) {
      throw new Exception('Permissão negada.');
    }
  }

  protected function getByAdmin ( $user )
  {
    if ( $user['email'] == 'suporte@dindigital.com' ) {
      $user_permissions = array();
      foreach ( Entities::$entities as $tbl => $entity ) {
        $user_permissions[] = $entity['name'];
      }
    } else {
      $user_permissions = json_decode($user['permission']);
    }

    $user_permissions = array_fill_keys($user_permissions, '');

    return $user_permissions;
  }

  public function getMenu ( $user )
  {
    $user_permissions = $this->getByAdmin($user);

    $m = new FileMenu('config/menu.php');
    $full_menu = $m->getArray();

    $user_menu = array();
    foreach ( $full_menu as $section => $specs ) {
      if ( array_key_exists('submenu', $specs) ) {
        foreach ( $specs['submenu'] as $subsection => $subspecs ) {
          if ( array_key_exists($subspecs['name'], $user_permissions) ) {
            $user_menu[$section]['submenu'][$subsection] = $subspecs;

            $entity = Entities::getEntityByName($subspecs['name']);
            $user_menu[$section]['submenu'][$subsection]['index'] = "/admin/{$entity['tbl']}/{$subspecs['index']}/";
          }
        }
      } else {
        if ( array_key_exists($specs['name'], $user_permissions) ) {
          $user_menu[$section] = $specs;

          $entity = Entities::getEntityByName($specs['name']);
          $user_menu[$section]['index'] = "/admin/{$entity['tbl']}/{$specs['index']}/";
        }
      }
    }

    return $user_menu;
  }

}

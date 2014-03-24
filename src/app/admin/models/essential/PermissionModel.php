<?php

namespace src\app\admin\models\essential;

use Exception;
use src\app\admin\helpers\FileMenu;
use src\app\admin\models\AdminModel;

/**
 *
 * @package app.models
 */
class PermissionModel extends BaseModelAdm
{

  public function getArrayList ()
  {
    $arrOptions = array();
    foreach ( $this->_entities->getAllEntities() as $tbl => $entity ) {
      if ( $entity->getSection() ) {
        $arrOptions[$entity->getTbl()] = $entity->getSection();
      }
    }

    return $arrOptions;
  }

  public function block ( $model, $user )
  {
    $permissoes = $this->getByAdmin($user);
    $tbl = $model->_entity->getTbl();

    if ( !array_key_exists($tbl, $permissoes) ) {
      throw new Exception('Permissão negada.');
    }
  }

  protected function getByAdmin ( $user )
  {
    if ( $user['id_admin'] == AdminModel::$_master_id ) {
      $user_permissions = array();
      foreach ( $this->_entities->getAllEntities() as $tbl => $entity ) {
        $user_permissions[] = $tbl;
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
          if ( array_key_exists($subspecs['tbl'], $user_permissions) ) {
            $user_menu[$section]['submenu'][$subsection] = $subspecs;

            $entity = $this->_entities->getEntity($subspecs['tbl']);
            $user_menu[$section]['submenu'][$subsection]['index'] = "/admin/{$entity->getTbl()}/{$subspecs['index']}/";
          }
        }
      } else {
        if ( array_key_exists($specs['tbl'], $user_permissions) ) {
          $user_menu[$section] = $specs;

          $entity = $this->_entities->getEntity($specs['tbl']);
          $user_menu[$section]['index'] = "/admin/{$entity->getTbl()}/{$specs['index']}/";
        }
      }
    }

    return $user_menu;
  }

}

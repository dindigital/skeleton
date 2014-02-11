<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
use Exception;

/**
 *
 * @package app.models
 */
class PermissionModel extends BaseModelAdm
{

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

    $menu = array();

    foreach ( $user_permissions as $name => $val ) {
      $entity = Entities::getEntityByName($name);

      if ( !isset($entity['index']) ) {
        continue;
      }

      $menu[$name]['uri'] = "/admin/{$entity['tbl']}/{$entity['index']}/";
      $menu[$name]['section'] = $entity['section'];

      if ( isset($entity['children']) ) {

        foreach ( $entity['children'] as $child ) {
          $child_entity = Entities::$entities[$child];

          if ( !isset($child_entity['index']) || !isset($user_permissions[$child_entity['name']]) ) {
            continue;
          }

          $menu[$name]['children'][$child_entity['name']] = array(
              'uri' => "/admin/{$child_entity['tbl']}/{$child_entity['index']}/",
              'section' => $child_entity['section'],
          );
        }
      }
    }

    return $this->remove_duplication($menu);
  }

  protected function remove_duplication ( $menu )
  {
    foreach ( $menu as $name => $spec ) {
      if ( isset($spec['children']) ) {
        foreach ( $spec['children'] as $children_name => $children_spec ) {
          unset($menu[$children_name]);
        }
      }
    }

    return $menu;
  }

}

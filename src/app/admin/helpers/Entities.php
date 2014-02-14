<?php

namespace src\app\admin\helpers;

use Din\File\Files;
use Exception;

class Entities
{

  public static $entities;

  public static function readFile ( $file )
  {
    if ( !Files::exists($file) )
      throw new Exception('Arquivo de entidades não encontrado: ' . $file);

    self::$entities = Files::get_return($file);
    self::understandEntities();
  }

  private static function understandEntities ()
  {
    foreach ( self::$entities as $i => $entity ) {
      $add_path = in_array($entity['name'], array('Gallery', 'Log')) ? '\essential' : '';

      self::$entities[$i]['model'] = '\src\app\admin\models' . $add_path . '\\' . $entity['name'] . 'Model';
      self::$entities[$i]['tbl'] = $i;
      //self::$entities[$i]['validator'] = '\src\app\admin\validators\\' . $entity['name'] . 'Validator';
    }
  }

  public static function getTrashItens ()
  {
    $r = self::$entities;
    foreach ( self::$entities as $tbl => $item ) {
      if ( !(isset($item['trash']) && $item['trash']) ) {
        unset($r[$tbl]);
      }
    }

    return $r;
  }

  public static function getEntity ( $tbl )
  {
    if ( array_key_exists($tbl, self::$entities) ) {
      return self::$entities[$tbl];
    } else {
      throw new Exception('Entidade não cadastrada: ' . $tbl);
    }
  }

  public static function getEntityByName ( $name )
  {
    $r = '';
    foreach ( self::$entities as $tbl ) {
      if ( $tbl['name'] == $name ) {
        $r = $tbl;
        break;
      }
    }

    if ( $r == '' )
      throw new Exception('Entidade não cadastrada: ' . $name);

    return $r;
  }

  public static function getThis ( $model )
  {
    $namespace = '\\' . get_class($model);

    $r = '';
    foreach ( self::$entities as $tbl ) {
      if ( $tbl['model'] == $namespace ) {
        $r = $tbl;
        break;
      }
    }

    if ( $r == '' ) {
      foreach ( self::$entities as $tbl ) {
        if ( isset($tbl['names']) ) {
          foreach ( $tbl['names'] as $names ) {
            if ( $names == $namespace ) {
              $r = $tbl;
              break;
            }
          }
        }
      }
    }

    if ( $r == '' )
      throw new Exception('Model não cadastrado na entidade: ' . $namespace);

    return $r;
  }

  public static function getChildren ( $tbl )
  {
    $r = array();

    $current = self::getEntity($tbl);
    if ( array_key_exists('children', $current) ) {
      foreach ( $current['children'] as $children ) {
        $r[$children] = self::$entities[$children];
      }
    }

    return $r;
  }

  public static function getParent ( $tbl )
  {
    $current = self::getEntity($tbl);
    if ( array_key_exists('parent', $current) ) {
      return self::$entities[$current['parent']];
    }
  }

}

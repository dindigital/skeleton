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
      self::$entities[$i]['model'] = '\src\app\admin\models\\' . $entity['name'] . 'Model';
      self::$entities[$i]['validator'] = '\src\app\admin\validators\\' . $entity['name'] . 'Validator';
    }
  }

  public static function getLixeiraItens ()
  {
    $r = self::$entities;
    foreach ( self::$entities as $tbl => $item ) {
      if ( !(isset($item['lixeira']) && $item['lixeira']) ) {
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

    if ( $r == '' )
      throw new Exception('Model não cadastrado na entidade: ' . $namespace);

    return $r;
  }

  public static function getFilhos ( $tbl )
  {
    $r = array();

    $atual = self::getEntity($tbl);
    if ( array_key_exists('filho', $atual) ) {
      $filhos = $atual['filho'];
      foreach ( $filhos as $filho ) {
        $r[$filho] = self::$entities[$filho];
      }
    }

    return $r;
  }

  public static function getPai ( $tbl )
  {
    $atual = self::getEntity($tbl);
    if ( array_key_exists('pai', $atual) ) {
      return self::$entities[$atual['pai']];
    }
  }

}

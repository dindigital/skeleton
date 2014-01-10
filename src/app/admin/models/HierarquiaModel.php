<?php

namespace src\app\admin\models;

use Din\DataAccessLayer\Select;

/**
 *
 * @package app.models
 */
class HierarquiaModel
{

  private static $_config = array(
      'noticia_cat' => array(
          'model' => 'NoticiaCatModel',
          'id' => 'id_noticia_cat',
          'filho' => array(
              'noticia'
          )
      ),
      'noticia' => array(
          'model' => 'NoticiaModel',
          'id' => 'id_noticia',
          'pai' => 'noticia_cat'
      ),
  );

  public static function getFilhos ( $tbl )
  {
    $r = array();

    if ( array_key_exists($tbl, self::$_config) ) {
      $id_pai = self::$_config[$tbl]['id'];
      if ( array_key_exists('filho', self::$_config[$tbl]) ) {
        $filhos = self::$_config[$tbl]['filho'];

        foreach ( $filhos as $filho ) {
          $r[$filho]['id_pai'] = $id_pai;
          $r[$filho]['id'] = self::$_config[$filho]['id'];
          $r[$filho]['tbl'] = $filho;
          $r[$filho]['model'] = self::$_config[$filho]['model'];
        }
      }
    }

    return $r;
  }

  public static function getPai ( $tbl )
  {
    if ( array_key_exists($tbl, self::$_config) ) {
      if ( array_key_exists('pai', self::$_config[$tbl]) ) {
        $model = self::$_config[$tbl]['model'];
        $tbl_pai = self::$_config[$tbl]['pai'];
        $id_pai = self::$_config[$tbl_pai]['id'];

        return array(
            'tbl' => $tbl_pai,
            'id' => $id_pai,
            'model' => $model
        );
      }
    }
  }

  public function excluirFilhos ( $tbl, $id, $dao )
  {
    $filhos = self::getFilhos($tbl);

    foreach ( $filhos as $filho ) {
      $select = new Select($filho['tbl']);
      $select->addField($filho['id']);
      $select->where(array(
          $filho['id_pai'] . ' = ? ' => $id
      ));
      $result = $dao->select($select);

      foreach ( $result as $row ) {
        $model_name = '\src\app\admin\models\\' . $filho['model'];
        $model_inst = new $model_name;
        $model_inst->excluir($row[$filho['id']]);
      }
    }
  }

}

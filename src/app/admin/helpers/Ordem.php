<?php

namespace src\app\admin\helpers;

use Din\DataAccessLayer\Entities;
use Din\Form\Dropdown\Dropdown;

class Ordem
{

  public static function setDropdown ( $model, $result, $arrCriteria )
  {
    $atual = Entities::getThis($model);
    if ( !isset($atual['ordem']) )
      return $result;

    $dependencia_criteria = array();
    if ( isset($atual['ordem']['dependencia']) ) {
      $dependencia_field = $atual['ordem']['dependencia'];

      foreach ( $arrCriteria as $field => $value ) {
        if ( strpos($field, $dependencia_field) !== false ) {
          $dependencia_criteria[$field] = $value;
          break;
        }
      }

      if ( !count($dependencia_criteria) )
        return $result;
    }

    $total = $model->getMaxOrdem($dependencia_criteria);
    $opcional = $atual['ordem']['opcional'];

    $options = array();
    for ( $i = 1; $i <= $total; $i++ ) {
      $options[$i] = $i;
    }

    foreach ( $result as $i => $row ) {
      $d = new Dropdown('ordem');

      if ( $opcional && $row['ordem'] == 0 ) {
        $options2 = $options;
        $options2[(string) $total + 1] = (string) $total + 1;
        $d->setOptionsArray($options2);
      } else {
        $d->setOptionsArray($options);
      }

      $d->setClass('form-control drop_ordem');
      $d->setSelected($row['ordem']);
      $d->setId($row[$atual['id']]);
      if ( $opcional )
        $d->setFirstOpt('');

      $result[$i]['ordem'] = $d->getElement();
    }

    return $result;
  }

  public static function setOrdem ( $model, $validator, $result = null )
  {
    $atual = Entities::getThis($model);
    if ( !isset($atual['ordem']) )
      return $result;

    $arrCriteria = array();

    if ( $atual['ordem']['opcional'] ) {
      $validator->setOrdem(0);
    } else {
      if ( isset($atual['ordem']['dependencia']) ) {
        $dependencia_field = $atual['ordem']['dependencia'];
        $dependencia_value = $result ? $result[$dependencia_field] : $validator->getTable()->{$dependencia_field};

        if ( is_null($dependencia_value) ) {
          $arrCriteria[$dependencia_field . ' IS NULL'] = null;
        } else {
          $arrCriteria[$dependencia_field . ' = ?'] = $dependencia_value;
        }
      }

      $ordem = $model->getMaxOrdem($arrCriteria) + 1;

      $validator->setOrdem($ordem);
    }
  }

  public static function changeOrdem ( $model, $id, $ordem )
  {
    $atual = Entities::getThis($model);

    if ( !isset($atual['ordem']) )
      return;

    $result = $model->getById($id);
    $ordem_antiga = $result['ordem'];

    $arrCriteria = array();

    if ( isset($atual['lixeira']) ) {
      $arrCriteria['del = 0'] = null;
    }

    if ( isset($atual['ordem']['dependencia']) ) {
      $dependencia_field = $atual['ordem']['dependencia'];
      $dependencia_value = $result[$dependencia_field];

      if ( is_null($dependencia_value) ) {
        $arrCriteria[$dependencia_field . ' IS NULL'] = null;
      } else {
        $arrCriteria[$dependencia_field . ' = ?'] = $dependencia_value;
      }
    }

//    if ( intval($ordem_antiga) == 0 && $trash )
//      return;
    //_# Opcionais
    if ( $ordem_antiga == 0 ) {
      $arrCriteria['ordem >= ?'] = $ordem;
      $result = $model->operaOrdem('+', $arrCriteria);
      //'0 =  ?' => $ordem_antiga
    } else if ( $ordem == 0 ) {

      $arrCriteria['ordem > ?'] = $ordem_antiga;
      $result = $model->operaOrdem('-', $arrCriteria);
      ///'0 =  ?' => $ordem
      //
      //_# Obrigatórios
    } else {
      if ( $ordem < $ordem_antiga ) {
        $arrCriteria['ordem >= ?'] = $ordem;
        $arrCriteria['ordem <= ?'] = $ordem_antiga;
        $result = $model->operaOrdem('+', $arrCriteria);
      } else {
        $arrCriteria['ordem <= ?'] = $ordem;
        $arrCriteria['ordem >= ?'] = $ordem_antiga;
        $result = $model->operaOrdem('-', $arrCriteria);
      }
    }

    $model->atualizaOrdem($ordem, $id);
  }

}

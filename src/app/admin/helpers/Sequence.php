<?php

namespace src\app\admin\helpers;

use src\app\admin\helpers\Entities;
use Din\Form\Dropdown\Dropdown;

class Sequence
{

  public static function setDropdown ( $model, $result, $arrCriteria )
  {
    $current = Entities::getThis($model);
    if ( !isset($current['sequence']) )
      return $result;

    $dependenceCriteria = array();
    if ( isset($current['sequence']['dependence']) ) {
      $dependenceField = $current['sequence']['dependence'];

      foreach ( $arrCriteria as $field => $value ) {
        if ( strpos($field, $dependenceField) !== false ) {
          $dependenceCriteria[$field] = $value;
          break;
        }
      }

      if ( !count($dependenceCriteria) )
        return $result;
    }

    $total = $model->getMaxSequence($dependenceCriteria);
    $optional = $current['sequence']['opcional'];

    $options = array();
    for ( $i = 1; $i <= $total; $i++ ) {
      $options[$i] = $i;
    }

    foreach ( $result as $i => $row ) {
      $d = new Dropdown('sequence');

      if ( $optional && $row['sequence'] == 0 ) {
        $options2 = $options;
        $options2[(string) $total + 1] = (string) $total + 1;
        $d->setOptionsArray($options2);
      } else {
        $d->setOptionsArray($options);
      }

      $d->setClass('form-control drop_sequence');
      $d->setSelected($row['sequence']);
      $d->setId($row[$current['id']]);
      if ( $optional )
        $d->setFirstOpt('');

      $result[$i]['sequence'] = $d->getElement();
    }

    return $result;
  }

  public static function setSequence ( $model, $validator, $result = null )
  {
    $current = Entities::getThis($model);
    if ( !isset($current['sequence']) )
      return $result;

    $arrCriteria = array();

    if ( $current['sequence']['opcional'] ) {
      $validator->setOrdem(0);
    } else {
      if ( isset($current['sequence']['dependencia']) ) {
        $dependenceField = $current['sequence']['dependencia'];
        $dependencia_value = $result ? $result[$dependenceField] : $validator->getTable()->{$dependenceField};

        if ( is_null($dependencia_value) ) {
          $arrCriteria[$dependenceField . ' IS NULL'] = null;
        } else {
          $arrCriteria[$dependenceField . ' = ?'] = $dependencia_value;
        }
      }

      $sequence = $model->getMaxOrdem($arrCriteria) + 1;

      $validator->setOrdem($sequence);
    }
  }

  public static function changeSequence ( $model, $id, $sequence )
  {
    $current = Entities::getThis($model);

    if ( !isset($current['sequence']) )
      return;

    $result = $model->getById($id);
    $sequence_old = $result['sequence'];

    $arrCriteria = array();

    if ( isset($current['lixeira']) ) {
      $arrCriteria['del = 0'] = null;
    }

    if ( isset($current['sequence']['dependencia']) ) {
      $dependenceField = $current['sequence']['dependencia'];
      $dependencia_value = $result[$dependenceField];

      if ( is_null($dependencia_value) ) {
        $arrCriteria[$dependenceField . ' IS NULL'] = null;
      } else {
        $arrCriteria[$dependenceField . ' = ?'] = $dependencia_value;
      }
    }

    //_# Opcionais
    if ( $sequence_old == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence;
      $result = $model->operateSequence('+', $arrCriteria);
    } else if ( $sequence == 0 ) {

      $arrCriteria['sequence > ?'] = $sequence_old;
      $result = $model->operateSequence('-', $arrCriteria);
      //_# Obrigatórios
    } else {
      if ( $sequence < $sequence_old ) {
        $arrCriteria['sequence >= ?'] = $sequence;
        $arrCriteria['sequence <= ?'] = $sequence_old;
        $result = $model->operateSequence('+', $arrCriteria);
      } else {
        $arrCriteria['sequence <= ?'] = $sequence;
        $arrCriteria['sequence >= ?'] = $sequence_old;
        $result = $model->operateSequence('-', $arrCriteria);
      }
    }

    $model->updateSequence($sequence, $id);
  }

}

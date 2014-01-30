<?php

namespace src\app\admin\helpers;

use src\app\admin\helpers\Entities;

class Sequence
{

  public static function setListArray ( $model, $result, $arrCriteria )
  {
    $current = Entities::getThis($model);
    if ( !isset($current['sequence']) )
      return $result;

    $dependenceCriteria = array();
    if ( isset($current['sequence']['dependence']) ) {
      $dependence_field = $current['sequence']['dependence'];

      foreach ( $arrCriteria as $field => $value ) {
        if ( strpos($field, $dependence_field) !== false ) {
          $dependenceCriteria[$field] = $value;
          break;
        }
      }

      if ( !count($dependenceCriteria) )
        return $result;
    }

    $total = $model->getMaxSequence($dependenceCriteria);
    $optional = $current['sequence']['optional'];

    $options = array();

    if ( $optional ) {
      $options[0] = '';
    }

    for ( $i = 1; $i <= $total; $i++ ) {
      $options[$i] = $i;
    }


    foreach ( $result as $i => $row ) {
      if ( $optional && $row['sequence'] == 0 ) {
        $options2 = $options;
        $options2[(string) $total + 1] = (string) $total + 1;

        $result[$i]['sequence_list_array'] = $options2;
      } else {
        $result[$i]['sequence_list_array'] = $options;
      }
    }

    return $result;
  }

  public static function setSequence ( $model, $validator, $result = null )
  {
    $current = Entities::getThis($model);
    if ( !isset($current['sequence']) )
      return $result;

    $arrCriteria = array();

    if ( $current['sequence']['optional'] ) {
      $validator->setSequence(0);
    } else {
      if ( isset($current['sequence']['dependence']) ) {
        $dependence_field = $current['sequence']['dependence'];
        $dependence_value = $result ? $result[$dependence_field] : $validator->getTable()->{$dependence_field};

        if ( is_null($dependence_value) ) {
          $arrCriteria[$dependence_field . ' IS NULL'] = null;
        } else {
          $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
        }
      }

      $sequence = $model->getMaxSequence($arrCriteria) + 1;

      $validator->setSequence($sequence);
    }
  }

  public static function changeSequence ( $model, $id, $sequence )
  {
    $current = Entities::getThis($model);

    if ( !isset($current['sequence']) )
      return;

    $result = $model->getById($id);
    $sequence_old = intval($result['sequence']);
    $sequence = intval($sequence);

    $arrCriteria = array();

    if ( isset($current['trash']) && $current['trash'] ) {
      $arrCriteria['is_del = 0'] = null;
    }

    if ( isset($current['sequence']['dependence']) ) {
      $dependence_field = $current['sequence']['dependence'];
      $dependence_value = $result[$dependence_field];

      if ( is_null($dependence_value) ) {
        $arrCriteria[$dependence_field . ' IS NULL'] = null;
      } else {
        $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
      }
    }

    $optional = isset($current['sequence']['optional']) && $current['sequence']['optional'];

    if ( $sequence == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence_old;
      $result = $model->operateSequence('-', $arrCriteria);
    } else if ( $sequence_old == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence;
      $result = $model->operateSequence('+', $arrCriteria);
    } else if ( $sequence < $sequence_old ) {
      $arrCriteria['sequence >= ?'] = $sequence;
      $arrCriteria['sequence <= ?'] = $sequence_old;
      $result = $model->operateSequence('+', $arrCriteria);
    } else {
      $arrCriteria['sequence <= ?'] = $sequence;
      $arrCriteria['sequence >= ?'] = $sequence_old;
      $result = $model->operateSequence('-', $arrCriteria);
    }

    $model->updateSequence($sequence, $id);
  }

}

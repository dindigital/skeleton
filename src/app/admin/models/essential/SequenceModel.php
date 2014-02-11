<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;

class SequenceModel extends BaseModelAdm
{
  /*
   * ===========================================================================
   * PROTECTED
   * ===========================================================================
   */

  protected $_model;

  protected function getMaxSequence ( $arrCriteria = array() )
  {
    $current = Entities::getThis($this->_model);

    $select = new Select($current['tbl']);

    if ( $current['sequence']['optional'] ) {
      $arrCriteria['sequence > ?'] = '0';
    }

    if ( isset($current['trash']) ) {
      $arrCriteria['is_del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);
  }

  protected function operateSequence ( $operator, $arrCriteria )
  {
    $current = Entities::getThis($this->_model);

    $SQL = "UPDATE {$current['tbl']} SET sequence = sequence {$operator} 1";
    $result = $this->_dao->execute($SQL, $arrCriteria);

    return $result;
  }

  protected function updateSequence ( $sequence, $id )
  {
    $current = Entities::getThis($this->_model);

    $this->_model->setIntval('sequence', $sequence);
    $this->_model->setId($id);

    $this->_dao->update($this->_model->getTable(), array($current['id'] . ' = ? ' => $id));
  }

  /*
   * ===========================================================================
   * PUBLIC
   * ===========================================================================
   */

  public function __construct ( $model )
  {
    parent::__construct();
    $this->_model = $model;
  }

  public function setSequence ( $result = null )
  {
    $current = Entities::getThis($this->_model);
    if ( !isset($current['sequence']) )
      return $result;

    $arrCriteria = array();

    if ( $current['sequence']['optional'] ) {
      $this->_model->setIntval('sequence', 0);
    } else {
      if ( isset($current['sequence']['dependence']) ) {
        $dependence_field = $current['sequence']['dependence'];
        $dependence_value = $result ? $result[$dependence_field] : $this->_model->getTable()->{$dependence_field};

        if ( is_null($dependence_value) ) {
          $arrCriteria[$dependence_field . ' IS NULL'] = null;
        } else {
          $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
        }
      }

      $sequence = $this->getMaxSequence($arrCriteria) + 1;

      $this->_model->setIntval('sequence', $sequence);
    }
  }

  public function setListArray ( $result, $arrCriteria )
  {
    $current = Entities::getThis($this->_model);
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

    $total = $this->getMaxSequence($dependenceCriteria);
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

  public function changeSequence ( $id, $sequence )
  {
    $current = Entities::getThis($this->_model);

    if ( !isset($current['sequence']) )
      return;

    $result = $this->_model->getById($id);
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

    if ( $sequence == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence_old;
      $result = $this->operateSequence('-', $arrCriteria);
    } else if ( $sequence_old == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence;
      $result = $this->operateSequence('+', $arrCriteria);
    } else if ( $sequence < $sequence_old ) {
      $arrCriteria['sequence >= ?'] = $sequence;
      $arrCriteria['sequence <= ?'] = $sequence_old;
      $result = $this->operateSequence('+', $arrCriteria);
    } else {
      $arrCriteria['sequence <= ?'] = $sequence;
      $arrCriteria['sequence >= ?'] = $sequence_old;
      $result = $this->operateSequence('-', $arrCriteria);
    }

    $this->updateSequence($sequence, $id);
  }

}
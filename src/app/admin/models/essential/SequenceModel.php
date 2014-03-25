<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\filters\TableFilter;
use Din\DataAccessLayer\Table\Table;

class SequenceModel extends BaseModelAdm
{

  protected $_model;

  public function __construct ( $model )
  {
    parent::__construct();
    $this->_model = $model;
  }

  protected function getMaxSequence ( $arrCriteria = array() )
  {
    $entity_tbl = $this->_model->_entity->getTbl();
    $entity_sequence = $this->_model->_entity->getSequence();

    $select = new Select($entity_tbl);

    if ( $entity_sequence['optional'] ) {
      $arrCriteria['sequence > ?'] = '0';
    }

    if ( $this->_entity->hasTrash() ) {
      $arrCriteria['is_del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);
  }

  protected function operateSequence ( $operator, $arrCriteria )
  {
    $entity_tbl = $this->_model->_entity->getTbl();

    $SQL = "UPDATE {$entity_tbl} SET sequence = sequence {$operator} 1";
    $result = $this->_dao->execute($SQL, $arrCriteria);

    return $result;
  }

  protected function updateSequence ( $sequence, $id )
  {
    $entity_id = $this->_model->_entity->getId();
    $entity_tbl = $this->_model->_entity->getTbl();

    $table = new Table($entity_tbl);
    $filter = new TableFilter($table, array(
        'sequence' => $sequence
    ));
    $filter->setIntval('sequence');

    $this->_dao->update($table, array($entity_id . ' = ? ' => $id));
  }

  public function setSequence ( $result = null )
  {
    $entity_sequence = $this->_model->_entity->getSequence();

    if ( !count($entity_sequence) )
      return $result;

    $arrCriteria = array();

    if ( $entity_sequence['optional'] ) {
      $this->_model->setIntval('sequence', 0);
    } else {
      if ( isset($entity_sequence['dependence']) ) {
        $dependence_field = $entity_sequence['dependence'];
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
    $entity = $this->_model->_entity;
    $entity_sequence = $entity->getSequence();

    if ( !count($entity_sequence) )
      return $result;

    $dependenceCriteria = array();
    if ( isset($entity_sequence['dependence']) ) {
      $dependence_field = $entity_sequence['dependence'];

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
    $optional = $entity_sequence['optional'];

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
    $entity = $this->_model->_entity;
    $entity_sequence = $entity->getSequence();

    if ( !count($entity_sequence) )
      return;

    $result = $this->_model->getById($id);
    $sequence_old = intval($result['sequence']);
    $sequence = intval($sequence);

    $arrCriteria = array();

    if ( $entity->hasTrash() ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    if ( isset($entity_sequence['dependence']) ) {
      $dependence_field = $entity_sequence['dependence'];
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

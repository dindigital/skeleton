<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\DataAccessLayer\Table\Table;

class SequenceModel extends BaseModelAdm
{

  protected $_model;

  protected function operateSequence ( $operator, $arrCriteria )
  {
    $entity_tbl = $this->_entity->getTbl();

    $SQL = "UPDATE {$entity_tbl} SET sequence = sequence {$operator} 1";
    $result = $this->_dao->execute($SQL, $arrCriteria);

    return $result;

  }

  protected function setModel ( $model )
  {
    $this->_model = $model;

  }

  /**
   *
   * @param type $input array (
   * 'tbl'=>'',
   * 'id'=>'',
   * 'sequence'=>''
   * )
   */
  public function changeSequence ( $input )
  {
    $this->_entity = $this->_entities->getEntity($input['tbl']);
    $entity_sequence = $this->_entity->getSequence();

    $this->setModel($this->_entity->getModel());

    $row = $this->_model->getById($input['id']);
    $sequence_old = intval($row['sequence']);
    $sequence_new = intval($input['sequence']);

    $arrCriteria = array();

    if ( $this->_entity->hasTrash() ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    if ( isset($entity_sequence['dependence']) ) {
      $dependence_field = $entity_sequence['dependence'];
      $dependence_value = $row[$dependence_field];

      $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
    }

    if ( $sequence_new == 0 ) {
      $arrCriteria['sequence > ?'] = $sequence_old;
      $result = $this->operateSequence('-', $arrCriteria);
    } else if ( $sequence_old == 0 ) {
      $arrCriteria['sequence >= ?'] = $sequence_new;
      $result = $this->operateSequence('+', $arrCriteria);
    } else if ( $sequence_new < $sequence_old ) {
      $arrCriteria['sequence >= ?'] = $sequence_new;
      $arrCriteria['sequence <= ?'] = $sequence_old;
      $result = $this->operateSequence('+', $arrCriteria);
    } else {
      $arrCriteria['sequence <= ?'] = $sequence_new;
      $arrCriteria['sequence >= ?'] = $sequence_old;
      $result = $this->operateSequence('-', $arrCriteria);
    }

    $this->updateSequence($sequence_new, $input['id']);

  }

  protected function updateSequence ( $sequence, $id )
  {
    $entity_id = $this->_entity->getId();
    $entity_tbl = $this->_entity->getTbl();

    $table = new Table($entity_tbl);
    $f = new TableFilter($table, array(
        'sequence' => $sequence
    ));
    $f->intval()->filter('sequence');

    $this->_dao->update($table, array($entity_id . ' = ? ' => $id));

  }

}

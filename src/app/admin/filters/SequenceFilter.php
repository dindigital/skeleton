<?php

namespace src\app\admin\filters;

use Din\DataAccessLayer\Table\Table;
use Din\DataAccessLayer\DAO;
use src\app\admin\helpers\Entity;
use Din\DataAccessLayer\Select;

class SequenceFilter extends BaseFilter
{

  protected $_dao;
  protected $_entity;

  public function __construct ( Table $table, DAO $dao, Entity $entity )
  {
    $this->setTable($table);
    $this->_dao = $dao;
    $this->_entity = $entity;
  }

  // FILTERS ___________________________________________________________________
  public function setSequence ()
  {

    $entity_sequence = $this->_entity->getSequence();

    $arrCriteria = array();

    if ( $entity_sequence['optional'] ) {
      $sequence = 0;
    } else {
      if ( isset($entity_sequence['dependence']) ) {
        $dependence_field = $entity_sequence['dependence'];
        $dependence_value = $this->_table->{$dependence_field};

        $arrCriteria[$dependence_field . ' = ?'] = $dependence_value;
      }

      $sequence = $this->getMaxSequence($arrCriteria) + 1;
    }

    $this->_table->sequence = $sequence;
  }

  protected function getMaxSequence ( $arrCriteria = array() )
  {
    $entity_tbl = $this->_entity->getTbl();

    $select = new Select($entity_tbl);

    if ( $this->_entity->hasTrash() ) {
      $arrCriteria['is_del = 0'] = null;
    }

    $select->where($arrCriteria);

    return $this->_dao->select_count($select);
  }

}

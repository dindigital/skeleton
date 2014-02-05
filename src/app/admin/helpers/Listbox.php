<?php

namespace src\app\admin\helpers;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\RelationshipValidator;

class Listbox
{

  private $_dao;

  function __construct ( DAO $dao )
  {
    $this->_dao = $dao;
  }

  /**
   * Insert records into the relationship table in the database
   * @param String $tbl Table that receives the values
   * @param String $tblField Name of Principal Field
   * @param String $tblId Value of Principal Field
   * @param String $relationshipField Name of Secondary  Field
   * @param Array $relationship Value of Secondary  Field
   */
  public function insertRelationship ( $tbl, $tblField, $tblId, $relationshipField, $relationship )
  {
    $validator = new RelationshipValidator($tbl);
    $validator->$tblField = $tblId;
    $this->_dao->delete($tbl, array("{$tblField} = ?" => $tblId));
    foreach ( $relationship as $index => $row ) {
      $validator->$relationshipField = $row;
      $validator->sequence = $index;
      $this->_dao->insert($validator->getTable());
    }
  }

  public function totalArray ( $tbl, $fieldId, $fieldLabel )
  {
    $select = new Select($tbl);
    $select->addField($fieldId);
    $select->addField($fieldLabel);

    $result = $this->_dao->select($select);

    $array = array();
    foreach ( $result as $row ) {
      $array[$row[$fieldId]] = $row[$fieldLabel];
    }

    return $array;
  }

  public function selectedArray ( $tbl, $fieldId, $fieldLabel, $tblRelationship, $fieldWhere, $idWhere )
  {
    $arrCriteria = array(
        "b.{$fieldWhere} = ?" => $idWhere
    );

    $select = new Select($tbl);
    $select->addField($fieldId);
    $select->addField($fieldLabel);
    $select->inner_join($fieldId, Select::construct($tblRelationship));
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    $array = array();
    foreach ( $result as $row ) {
      $array[$row[$fieldId]] = $row[$fieldLabel];
    }

    return $array;
  }

  public function ajaxJson ( $tbl, $fieldId, $fieldLabel, $term )
  {
    $arrCriteria = array(
        "{$fieldLabel} LIKE ?" => '%' . $term . '%'
    );

    $select = new Select($tbl);
    $select->addField($fieldId, 'id');
    $select->addField($fieldLabel, 'label');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return json_encode($result);
  }

}


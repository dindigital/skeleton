<?php

namespace src\app\admin\helpers;

use Din\DataAccessLayer\DAO;
use Din\DataAccessLayer\Select;

class Listbox
{

  private $_dao;

  function __construct ( DAO $dao )
  {
    $this->_dao = $dao;
  }

  public function totalArray ( $tbl, $fieldId, $fieldLabel )
  {
    $select = new Select($tbl);
    $select->addField($fieldId);
    $select->addField($fieldLabel);
    $select->order_by('inc_data DESC');

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

    $selected = array();
    $relationship = array();
    foreach ( $result as $row ) {
      $selected[$row[$fieldId]] = $row[$fieldLabel];
      $relationship[] = $row[$fieldId];
    }

    $array = array(
        'selected' => $selected,
        'relationship' => $relationship
    );

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


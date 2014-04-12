<?php

namespace Admin\Models\Essential;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\DataAccessLayer\Table\Table;

class RelationshipModel extends BaseModelAdm
{

  protected $_current_entity;
  protected $_foreign_entity;

  /**
   * Seta a entidade da página atual
   * @param String $tbl
   */
  public function setCurrentEntity ( $tbl )
  {
    $this->_current_entity = $this->_entities->getEntity($tbl);

  }

  /**
   * Seta a entidade da página que será relacionada com a atual
   * @param String $tbl
   */
  public function setForeignEntity ( $tbl )
  {
    $this->_foreign_entity = $this->_entities->getEntity($tbl);

  }

  /**
   * Retorna os resultados da tabela relacionada quando usuário digita
   * no campo de texto
   * @param String $q Para que fará a consulta no banco por sugestões
   * @return String/Json
   */
  public function getAjax ( $q )
  {
    $fe_tbl = $this->_foreign_entity->getTbl();
    $fe_title = $this->_foreign_entity->getTitle();
    $fe_id = $this->_foreign_entity->getId();

    $arrCriteria["{$fe_title} LIKE ?"] = '%' . $q . '%';

    if ( $this->_foreign_entity->hasTrash() ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($fe_tbl);
    $select->addField($fe_id, 'id');
    $select->addField($fe_title, 'text');
    $select->where($arrCriteria);
    $select->order_by($fe_title);

    $result = $this->_dao->select($select);

    return $result;

  }

  /**
   *
   * @param String $id
   * @return String/Json
   */
  public function getAjaxCurrent ( $id )
  {
    $ce_tbl = $this->_current_entity->getTbl();
    $ce_id = $this->_current_entity->getId();

    $fe_tbl = $this->_foreign_entity->getTbl();
    $fe_id = $this->_foreign_entity->getId();
    $fe_title = $this->_foreign_entity->getTitle();

    $tableRelationship = "r_{$ce_tbl}_{$fe_tbl}";

    $arrCriteria["{$ce_id} = ?"] = $id;

    if ( $this->_foreign_entity->hasTrash() ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($fe_tbl);
    $select->addField($fe_id, 'id');
    $select->addField($fe_title, 'text');
    $select->inner_join($fe_id, Select::construct($tableRelationship));
    $select->where($arrCriteria);
    $select->order_by('b.sequence');

    $result = $this->_dao->select($select);

    return $result;

  }

  public function insert ( $id, $item )
  {
    $arrayItem = (trim($item) != '') ? explode(',', $item) : array();
    $arrayId = array();

    $model = $this->_foreign_entity->getModel();

    foreach ( $arrayItem as $row ) {
      if ( $this->count($row) ) {
        $arrayId[] = $row;
      } else if ( $discoverId = $this->getIdByTitle($row) ) {
        $arrayId[] = $discoverId;
      } else {
        $model->short_insert($row);
        $arrayId[] = $model->getId();
      }
    }

    $this->insertRelationship($arrayId, $id);

  }

  public function smartInsert ( $id, $item )
  {
    $arrayItem = explode(',', $item);
    $arrayId = array();

    foreach ( $arrayItem as $row ) {
      if ( $this->count($row) ) {
        $arrayId[] = $row;
      }
    }

    $this->insertRelationship($arrayId, $id);

  }

  private function count ( $row )
  {
    $fe_tbl = $this->_foreign_entity->getTbl();
    $fe_id = $this->_foreign_entity->getId();

    $arrCriteria["{$fe_id} = ?"] = $row;

    $select = new Select($fe_tbl);
    $select->addField($fe_id);
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return (bool) count($result);

  }

  private function getIdByTitle ( $row )
  {
    $fe_tbl = $this->_foreign_entity->getTbl();
    $fe_id = $this->_foreign_entity->getId();
    $fe_title = $this->_foreign_entity->getTitle();

    $arrCriteria["{$fe_title} = ?"] = $row;

    $select = new Select($fe_tbl);
    $select->addField($fe_id, 'id');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return count($result) ? $result[0]['id'] : false;

  }

  private function insertRelationship ( $arrayId, $id )
  {
    $ce_tbl = $this->_current_entity->getTbl();
    $ce_id = $this->_current_entity->getId();

    $fe_tbl = $this->_foreign_entity->getTbl();
    $fe_id = $this->_foreign_entity->getId();

    $tablename_relationship = "r_{$ce_tbl}_{$fe_tbl}";
    $table_relashionship = new Table($tablename_relationship);

    $fieldCurrent = $ce_id;
    $fieldRelationship = $fe_id;

    $this->_dao->delete($tablename_relationship, array("{$fieldCurrent} = ?" => $id));

    $table_relashionship->{$fieldCurrent} = $id;
    foreach ( $arrayId as $index => $row ) {
      $table_relashionship->$fieldRelationship = $row;
      $table_relashionship->sequence = $index;
      $this->_dao->insert($table_relashionship);
    }

  }

}

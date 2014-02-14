<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
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
    $this->_current_entity = Entities::getEntity($tbl);
  }

  /**
   * Seta a entidade da página que será relacionada com a atual
   * @param String $tbl
   */
  public function setForeignEntity ( $tbl )
  {
    $this->_foreign_entity = Entities::getEntity($tbl);
  }

  /**
   * Retorna os resultados da tabela relacionada quando usuário digita
   * no campo de texto
   * @param String $q Para que fará a consulta no banco por sugestões
   * @return String/Json
   */
  public function getAjax ( $q )
  {
    $arrCriteria["{$this->_foreign_entity['title']} LIKE ?"] = '%' . $q . '%';

    if ( isset($this->_foreign_entity['trash']) && $this->_foreign_entity['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->_foreign_entity['tbl']);
    $select->addField($this->_foreign_entity['id'], 'id');
    $select->addField($this->_foreign_entity['title'], 'text');
    $select->where($arrCriteria);
    $select->order_by($this->_foreign_entity['title']);

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

    $tableRelationship = "r_{$this->_current_entity['tbl']}_{$this->_foreign_entity['tbl']}";

    $arrCriteria["{$this->_current_entity['id']} = ?"] = $id;

    if ( isset($this->_foreign_entity['trash']) && $this->_current_entity['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->_foreign_entity['tbl']);
    $select->addField($this->_foreign_entity['id'], 'id');
    $select->addField($this->_foreign_entity['title'], 'text');
    $select->inner_join($this->_foreign_entity['id'], Select::construct($tableRelationship));
    $select->where($arrCriteria);
    $select->order_by('b.sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $id, $item )
  {
    $arrayItem = (trim($item) != '') ? explode(',', $item) : array();
    $arrayId = array();

    $model = new $this->_foreign_entity['model'];

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
    $arrCriteria["{$this->_foreign_entity['id']} = ?"] = $row;

    $select = new Select($this->_foreign_entity['tbl']);
    $select->addField($this->_foreign_entity['id']);
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return (bool) count($result);
  }

  private function getIdByTitle ( $row )
  {
    $arrCriteria["{$this->_foreign_entity['title']} = ?"] = $row;

    $select = new Select($this->_foreign_entity['tbl']);
    $select->addField($this->_foreign_entity['id'], 'id');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return count($result) ? $result[0]['id'] : false;
  }

  private function insertRelationship ( $arrayId, $id )
  {
    $tablename_relationship = "r_{$this->_current_entity['tbl']}_{$this->_foreign_entity['tbl']}";
    $table_relashionship = new Table($tablename_relationship);

    $fieldCurrent = $this->_current_entity['id'];
    $fieldRelationship = $this->_foreign_entity['id'];

    $this->_dao->delete($tablename_relationship, array("{$fieldCurrent} = ?" => $id));

    $table_relashionship->{$fieldCurrent} = $id;
    foreach ( $arrayId as $index => $row ) {
      $table_relashionship->$fieldRelationship = $row;
      $table_relashionship->sequence = $index;
      $this->_dao->insert($table_relashionship);
    }
  }

}

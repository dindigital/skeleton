<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use Din\DataAccessLayer\Table\Table;

class RelationshipModel extends BaseModelAdm
{

  private $currentSection;
  private $relationshipSection;
  private $entities;

  public function __construct ()
  {
    parent::__construct();
    $this->entities = new Entities();
  }

  /**
   * Seta a entidade da página atual
   * @param String $tbl
   */
  public function setCurrentSection ( $tbl )
  {
    $this->currentSection = $this->entities->getEntity($tbl);
  }

  /**
   * Seta a entidade da página que será relacionada com a atual
   * @param String $tbl
   */
  public function setRelationshipSection ( $tbl )
  {
    $this->relationshipSection = $this->entities->getEntity($tbl);
  }

  /**
   * Retorna os resultados da tabela relacionada quando usuário digita
   * no campo de texto
   * @param String $q Para que fará a consulta no banco por sugestões
   * @return String/Json
   */
  public function getAjax ( $q )
  {
    $arrCriteria["{$this->relationshipSection['title']} LIKE ?"] = '%' . $q . '%';

    if ( isset($this->relationshipSection['trash']) && $this->relationshipSection['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id'], 'id');
    $select->addField($this->relationshipSection['title'], 'text');
    $select->where($arrCriteria);
    $select->order_by($this->relationshipSection['title']);

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

    $tableRelationship = "r_{$this->currentSection['tbl']}_{$this->relationshipSection['tbl']}";

    $arrCriteria["{$this->currentSection['id']} = ?"] = $id;

    if ( isset($this->relationshipSection['trash']) && $this->currentSection['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id'], 'id');
    $select->addField($this->relationshipSection['title'], 'text');
    $select->inner_join($this->relationshipSection['id'], Select::construct($tableRelationship));
    $select->where($arrCriteria);
    $select->order_by('b.sequence');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $id, $item )
  {
    $arrayItem = (trim($item) != '') ? explode(',', $item) : array();
    $arrayId = array();

    $model = new $this->relationshipSection['model'];

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
    $arrCriteria["{$this->relationshipSection['id']} = ?"] = $row;

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id']);
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return (bool) count($result);
  }

  private function getIdByTitle ( $row )
  {
    $arrCriteria["{$this->relationshipSection['title']} = ?"] = $row;

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id'], 'id');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    return count($result) ? $result[0]['id'] : false;
  }

  private function insertRelationship ( $arrayId, $id )
  {
    $tablename_relationship = "r_{$this->currentSection['tbl']}_{$this->relationshipSection['tbl']}";
    $table_relashionship = new Table($tablename_relationship);

    $fieldCurrent = $this->currentSection['id'];
    $fieldRelationship = $this->relationshipSection['id'];

    $this->_dao->delete($tablename_relationship, array("{$fieldCurrent} = ?" => $id));

    $table_relashionship->{$fieldCurrent} = $id;
    foreach ( $arrayId as $index => $row ) {
      $table_relashionship->$fieldRelationship = $row;
      $table_relashionship->sequence = $index;
      $this->_dao->insert($table_relashionship);
    }
  }

}

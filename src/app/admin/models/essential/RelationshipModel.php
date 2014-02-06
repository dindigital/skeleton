<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\helpers\Entities;
use Din\DataAccessLayer\Select;
use src\app\admin\validators\RelationshipValidator;

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

    if ( $this->relationshipSection['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id'], 'id');
    $select->addField($this->relationshipSection['title'], 'text');
    $select->where($arrCriteria);
    $select->order_by($this->relationshipSection['title']);

    $result = $this->_dao->select($select);

    $jsonResult = json_encode($result);

    return $jsonResult;
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

    if ( $this->currentSection['trash'] == true ) {
      $arrCriteria['is_del = ?'] = '0';
    }

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id'], 'id');
    $select->addField($this->relationshipSection['title'], 'text');
    $select->inner_join($this->relationshipSection['id'], Select::construct($tableRelationship));
    $select->where($arrCriteria);
    $select->order_by('b.sequence');

    $result = $this->_dao->select($select);

    $jsonResult = json_encode($result);

    return $jsonResult;
  }

  public function insert ( $id, $item )
  {
    $arrayItem = explode(',', $item);
    $arrayId = array();

    $validator = new $this->relationshipSection['validator']();
    $set = 'set' . ucfirst($this->relationshipSection['title']);
    $model = new $this->relationshipSection['model']();

    foreach ( $arrayItem as $row ) {
      $this->setId($validator->setId($model));
      $validator->setActive(1);
      $validator->setIncDate();
      if ( $this->count($row) ) {
        $validator->$set($row);
        $this->_dao->insert($validator->getTable());
        $arrayId[] = $this->getId();
      } else {
        $arrayId[] = $row;
      }
    }

    $this->insert_relationship($arrayId, $id);
  }

  public function insert2 ( $id, $item )
  {
    $arrayItem = explode(',', $item);
    $arrayId = array();

    foreach ( $arrayItem as $row ) {
      if ( !$this->count($row) ) {
        $arrayId[] = $row;
      }
    }

    $this->insert_relationship($arrayId, $id);
  }

  private function count ( $row )
  {
    $arrCriteria["{$this->relationshipSection['id']} = ?"] = $row;

    $select = new Select($this->relationshipSection['tbl']);
    $select->addField($this->relationshipSection['id']);
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( count($result) )
      return false;

    return true;
  }

  private function insert_relationship ( $arrayId, $id )
  {
    $tableRelationship = "r_{$this->currentSection['tbl']}_{$this->relationshipSection['tbl']}";

    $validator = new RelationshipValidator($tableRelationship);
    $fieldCurrent = $this->currentSection['id'];
    $validator->$fieldCurrent = $id;
    $this->_dao->delete($tableRelationship, array("{$this->currentSection['id']} = ?" => $id));
    $fieldRelationship = $this->relationshipSection['id'];
    foreach ( $arrayId as $index => $row ) {
      $validator->$fieldRelationship = $row;
      $validator->sequence = $index;
      $this->_dao->insert($validator->getTable());
    }
  }

}


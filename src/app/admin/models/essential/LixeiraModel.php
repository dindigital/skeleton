<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use Din\Paginator\Paginator;
use Din\Form\Dropdown\Dropdown;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class LixeiraModel extends BaseModelAdm
{

  public function listar ( $arrFilters = array(), Paginator $paginator = null )
  {
    $itens = Entities::getLixeiraItens();

    if ( $arrFilters['secao'] != '0' ) {
      if ( isset($itens[$arrFilters['secao']]) ) {
        $itens = array($itens[$arrFilters['secao']]);
      }
    }

    $i = 0;
    foreach ( $itens as $item ) {

      $name = $item['name'];
      $table_name = $item['tbl'];
      $id_field = $item['id'];
      $title_field = $item['title'];
      $secao = $item['secao'];

      $select1 = new Select($table_name);
      $select1->addField($id_field, 'id');
      $select1->addField($title_field);
      $select1->addField('del_data');
      $select1->addSField('secao', $secao);
      $select1->addSField('name', $name);
      $select1->where(array(
          'del = 1' => null,
          $title_field . ' LIKE ?' => '%' . $arrFilters['titulo'] . '%'
      ));

      if ( $i == 0 ) {
        $select = $select1;
      } else {
        $select->union($select1);
      }

      $i++;
    }

    $select->order_by('del_data DESC');

    $this->setPaginationSelect($select, $paginator);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function restaurar ( $itens )
  {
    foreach ( $itens as $item ) {
      list($name, $id) = explode('_', $item);

      $atual = Entities::getEntityByName($name);

      $model = new $atual['model'];
      $model->restaurar($id);
    }
  }

  public function excluir ( $itens )
  {
    foreach ( $itens as $item ) {
      list($name, $id) = explode('_', $item);

      $atual = Entities::getEntityByName($name);
      $model = new $atual['model'];
      $model->excluir_permanente($id);
    }
  }

  public function getDropdown ( $firstOption = '', $selected = null )
  {
    $arrOptions = array();

    foreach ( Entities::getLixeiraItens() as $model ) {
      $arrOptions[$model['tbl']] = $model['secao'];
    }

    $d = new Dropdown('secao');
    $d->setOptionsArray($arrOptions);
    $d->setClass('form-control');
    $d->setSelected($selected);
    if ( $firstOption != '' ) {
      $d->setFirstOpt($firstOption);
    }

    return $d->getElement();
  }

  public function validateRestaurar ( $tbl, $id )
  {
    $atual = Entities::getEntity($tbl);
    $pai = Entities::getPai($tbl);

    if ( $pai ) {
      $model_inst = new $atual['model'];
      $row = $model_inst->getById($id);
      $id_pai = $row[$pai['id']];

      $select = new Select($pai['tbl']);
      $select->addField($pai['title'], 'titulo');
      $select->where(array(
          $pai['id'] . ' = ?' => $id_pai,
          'del = ?' => '1'
      ));

      $result = $this->_dao->select($select);

      if ( count($result) ) {
        throw new Exception('É necessário restaurar o ítem: ' . $pai['secao'] . ' - ' . $result[0]['titulo']);
      }
    }
  }

}

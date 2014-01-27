<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use Din\Paginator\Paginator;
use src\app\admin\helpers\Entities;

/**
 *
 * @package app.models
 */
class TrashModel extends BaseModelAdm
{

  public function get_list ( $arrFilters = array(), Paginator $paginator = null )
  {
    $itens = Entities::getTrashItens();

    if ( $arrFilters['section'] != '0' ) {
      if ( isset($itens[$arrFilters['section']]) ) {
        $itens = array($itens[$arrFilters['section']]);
      }
    }

    $i = 0;
    foreach ( $itens as $item ) {

      $name = $item['name'];
      $table_name = $item['tbl'];
      $id_field = $item['id'];
      $title_field = $item['title'];
      $section = $item['section'];

      $select1 = new Select($table_name);
      $select1->addField($id_field, 'id');
      $select1->addField($title_field);
      $select1->addField('del_date');
      $select1->addSField('section', $section);
      $select1->addSField('name', $name);
      $select1->where(array(
          'del = 1' => null,
          $title_field . ' LIKE ?' => '%' . $arrFilters['title'] . '%'
      ));

      if ( $i == 0 ) {
        $select = $select1;
      } else {
        $select->union($select1);
      }

      $i++;
    }

    $select->order_by('del_date DESC');

    $this->setPaginationSelect($select, $paginator);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function restore ( $itens )
  {
    foreach ( $itens as $item ) {
      list($name, $id) = explode('_', $item);

      $current = Entities::getEntityByName($name);

      $model = new $current['model'];
      $model->restore($id);
    }
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      list($name, $id) = explode('_', $item);

      $current = Entities::getEntityByName($name);
      $model = new $current['model'];
      $model->delete_permanent($id);
    }
  }

  public function getDropdown ()
  {
    $arrOptions = array();

    foreach ( Entities::getTrashItens() as $model ) {
      $arrOptions[$model['tbl']] = $model['section'];
    }

    return $arrOptions;
  }

  public function validateRestore ( $tbl, $id )
  {
    $current = Entities::getEntity($tbl);
    $parent = Entities::getPai($tbl);

    if ( $parent ) {
      $model = new $current['model'];
      $row = $model->getById($id);
      $id_parent = $row[$parent['id']];

      $select = new Select($parent['tbl']);
      $select->addField($parent['title'], 'title');
      $select->where(array(
          $parent['id'] . ' = ?' => $id_parent,
          'del = ?' => '1'
      ));

      $result = $this->_dao->select($select);

      if ( count($result) ) {
        throw new Exception('É necessário restaurar o ítem: ' . $parent['section'] . ' - ' . $result[0]['title']);
      }
    }
  }

}
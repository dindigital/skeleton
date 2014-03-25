<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Entities;
use src\app\admin\models\essential\SequenceModel;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\filters\TableFilter;
use src\app\admin\helpers\Entity;
use Din\DataAccessLayer\Table\Table;

/**
 *
 * @package app.models
 */
class TrashModel extends BaseModelAdm
{

  public function getList ()
  {
    $itens = $this->_entities->getTrashItens();

    if ( $this->_filters['section'] != '' && $this->_filters['section'] != '0' ) {
      //if ( isset($itens[$this->_filters['section']]) ) {
      $itens = array($itens[$this->_filters['section']]);
      //}
    }

    $i = 0;
    foreach ( $itens as $item ) {

      $table_name = $item->getTbl();
      $id_field = $item->getId();
      $title_field = $item->getTitle();
      $section = $item->getSection();

      $select1 = new Select($table_name);
      $select1->addField($id_field, 'id');
      $select1->addField($title_field, 'title');
      $select1->addField('del_date');
      $select1->addSField('section', $section);
      $select1->addSField('entity_name', $table_name);
      $select1->where(array(
          'is_del = 1' => null,
          $title_field . ' LIKE ?' => '%' . $this->_filters['title'] . '%'
      ));

      if ( $i == 0 ) {
        $select = $select1;
      } else {
        $select->union($select1);
      }

      $i++;
    }

    $select->order_by('del_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['del_date'] = DateFormat::filter_date($row['del_date'], 'd/m/Y H:i');
    }

    return $result;
  }

  private function hasParentOnTrash ( Entity $entity, $table )
  {
    $parent = $entity->getParent();
    if ( $parent ) {
      $parent_entity = $this->_entities->getEntity($parent);
      $parent_tbl = $parent_entity->getTbl();
      $parent_id_field = $parent_entity->getId();

      $parend_id_value = $table[$parent_id_field];

      $select = new Select($parent_tbl);
      $select->addField($parent['title'], 'title');
      $select->where(array(
          $parent_id_field . ' = ?' => $parend_id_value,
          'is_del = ?' => 1,
      ));

      $result = $this->_dao->select($select);
      if ( count($result) ) {
        return $result[0]['title'];
      }
    }
  }

  public function restore ( $itens )
  {
    foreach ( $itens as $item ) {
      $entity = $this->_entities->getEntity($item['name']);
      $entity_id = $entity->getId();
      $entity_title = $entity->getTitle();
      $entity_tbl = $entity->getTbl();

      $model = $entity->getModel();
      $tableHistory = $model->getById($item['id']);

      //
      if ( $parent_title = $this->hasParentOnTrash($entity, $tableHistory) ) {
        throw new Exception('Favor restaurar o ítem ' . $parent_title . ' primeiro');
      }
      //

      $seq = new SequenceModel($model);
      $seq->setSequence();

      $table = new Table($entity_tbl);
      $filter = new TableFilter($table, array(
          'is_del' => '0'
      ));
      $filter->setIntval('is_del');
      $filter->setNull('del_date');
      $this->_dao->update($table, array($$entity_id . ' = ?' => $item['id']));

      $this->log('R', $tableHistory[$entity_title], $table);
    }
  }

  public function deleteChildren ( Entity $entity, $id )
  {
    $entity_id = $entity->getId();
    $children = $entity->getChildren();

    foreach ( $children as $child ) {
      $child_entity = $this->_entities->getEntity($child);
      $child_tbl = $child_entity->getTbl();
      $child_id = $child_entity->getId();

      $select = new Select($child_tbl);
      $select->addField($child_id, 'id_children');
      $select->where(array(
          $entity_id . ' = ? ' => $id
      ));
      $result = $this->_dao->select($select);

      $arr_delete = array();
      foreach ( $result as $row ) {
        $arr_delete[] = array(
            'name' => $child_tbl,
            'id' => $row['id_children'],
        );
      }

      $this->delete($arr_delete);
    }
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      $entity = $this->_entities->getEntity($item['name']);
      $entity_id = $entity->getId();
      $entity_title = $entity->getTitle();
      $entity_tbl = $entity->getTbl();

      $model = $entity->getModel();

      //_# Se ele não possui lixeira, chama o deletar proprio do model
      if ( !$entity->hasTrash() ) {
        $model->delete(array(array('id' => $entity_id)));
      } else {

        $seq = new SequenceModel($model);
        $seq->changeSequence($item['id'], 0);

        $this->deleteChildren($entity, $item['id']);
        $tableHistory = $model->getById($item['id']);

        $table = new Table($entity_tbl);
        $filter = new TableFilter($table, array(
            'is_del' => '1'
        ));
        $filter->setTimestamp('del_date');
        $filter->setIntval('is_del');
        $this->_dao->update($table, array($entity_id . ' = ?' => $item['id']));
        $this->log('T', $tableHistory[$entity_title], $entity_tbl, $tableHistory);
      }
    }
  }

  public function delete_permanent ( $itens )
  {
    foreach ( $itens as $item ) {
      $current = Entities::getEntityByName($item['name']);

      $model = new $current['model'];
      $info = $model->getById($item['id']);

      //
      if ( $parent_title = $this->hasParentOnTrash($current, $info) ) {
        throw new Exception('Favor excluir o ítem ' . $parent_title . ' primeiro');
      }
      //

      $model->delete(array(array(
              'id' => $item['id']
      )));
    }
  }

  public function getListArray ()
  {
    $arrOptions = array();

    foreach ( $this->_entities->getTrashItens() as $entity ) {
      $arrOptions[$entity->getTbl()] = $entity->getSection();
    }

    return $arrOptions;
  }

  public function formatFilters ()
  {
    $dropdown_section = $this->getListArray();
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['section'] = Form::Dropdown('section', $dropdown_section, $this->_filters['section'], 'Filtro por Seção');

    return $this->_filters;
  }

}

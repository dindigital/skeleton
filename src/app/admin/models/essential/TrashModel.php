<?php

namespace src\app\admin\models\essential;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Exception;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\SequenceModel;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use src\app\admin\helpers\Entity;
use Din\DataAccessLayer\Table\Table;
use src\app\admin\filters\SequenceFilter;

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
      $itens = array($itens[$this->_filters['section']]);
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

  private function hasParentOnTrash ( Entity $entity, $row )
  {
    $parent = $entity->getParent();
    if ( $parent ) {
      $parent_entity = $this->_entities->getEntity($parent);
      $parent_tbl = $parent_entity->getTbl();
      $parent_id_field = $parent_entity->getId();
      $parent_title = $parent_entity->getTitle();

      $parend_id_value = $row[$parent_id_field];

      $select = new Select($parent_tbl);
      $select->addField($parent_title, 'title');
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
      $entity_sequence = $entity->getSequence();

      $model = $entity->getModel();
      $tableHistory = $model->getById($item['id']);

      //
      if ( $parent_title = $this->hasParentOnTrash($entity, $tableHistory) ) {
        throw new Exception('Favor restaurar o ítem ' . $parent_title . ' primeiro');
      }
      //

      $table = new Table($entity_tbl);
      $f = new TableFilter($table, array(
          'is_del' => '0'
      ));

      if ( count($entity_sequence) ) {
        $f->sequence($this->_dao, $entity)->filter('sequence');
      }

      $f->intval()->filter('is_del');
      $f->null()->filter('del_date');
      $this->_dao->update($table, array($entity_id . ' = ?' => $item['id']));

      $this->log('R', $tableHistory[$entity_title], $table);
    }
  }

  public function deleteChildrens ( Entity $entity, $id, $tableHistory )
  {
    $entity_id = $entity->getId();
    $entity_tbl = $entity->getTbl();
    $children = $entity->getChildren();

    // verificia se é página infinita para deletar uma filha dela caso exista
    if ( array_key_exists('id_parent', $tableHistory) ) {
      $model = $entity->getModel();

      $select = new Select($entity_tbl);
      $select->addField($entity_id, 'id');
      $select->where(array(
          'id_parent = ?' => $tableHistory[$entity_id]
      ));
      $result = $this->_dao->select($select);

      $arr_delete = array();
      foreach ( $result as $row ) {
        $arr_delete[] = array(
            'name' => $entity_tbl,
            'id' => $row['id'],
        );
      }

      $this->delete($arr_delete);
    }
    //

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
      $entity_sequence = $entity->getSequence();

      $model = $entity->getModel();

      //_# Se ele não possui lixeira, ignora
      if ( !$entity->hasTrash() ) {
        //ignore
      } else {

        if ( count($entity_sequence) ) {
          $seq = new SequenceModel();
          $seq->changeSequence(array(
              'tbl' => $entity_tbl,
              'id' => $item['id'],
              'sequence' => 0
          ));
        }

        $tableHistory = $model->getById($item['id']);
        $this->deleteChildrens($entity, $item['id'], $tableHistory);

        $table = new Table($entity_tbl);
        $f = new TableFilter($table, array(
            'is_del' => '1'
        ));
        $f->timestamp()->filter('del_date');
        $f->intval()->filter('is_del');
        $this->_dao->update($table, array($entity_id . ' = ?' => $item['id']));
        $this->log('T', $tableHistory[$entity_title], $table, $tableHistory);
      }
    }
  }

  public function delete_permanent ( $itens )
  {
    foreach ( $itens as $item ) {
      $entity = $this->_entities->getEntity($item['name']);

      $model = $entity->getModel();
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

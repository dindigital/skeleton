<?php

namespace src\app\admin\models;

use src\app\admin\validators\PageValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class PageModel extends BaseModelAdm
{

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    if ( $arrFilters['id_page_cat'] != '' && $arrFilters['id_page_cat'] != '0' ) {
      $arrCriteria['a.id_page_cat = ?'] = $arrFilters['id_page_cat'];
    }

    $select = new Select('page');
    $select->addField('id_page');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,a.title');

    $select->inner_join('id_page_cat', Select::construct('page_cat')
                    ->addField('title', 'menu'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);
    $result = Sequence::setListArray($this, $result, $arrCriteria);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator;
    $id = $validator->setId($this);
    $validator->setIdPageCat($info['id_page_cat']);
    $validator->setIdParent($info['id_parent']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->setIncDate();
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $id, $mf);
    Sequence::setSequence($this, $validator);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator;
    $validator->setIdPageCat($info['id_page_cat']);
    $validator->setIdParent($info['id_parent']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setContent($info['content']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $id, $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_page = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    return $id;
  }

  public function getNew ()
  {
    return array(
        'id_page' => null,
        'id_page_cat' => null,
        'id_parent' => null,
        'active' => null,
        'title' => null,
        'cover' => null,
        'content' => null,
        'description' => null,
        'keywords' => null,
        'inc_date' => null,
        'del_date' => null,
        'is_del' => null,
    );
  }

  public function getListArray ( $id_page_cat = null, $id_parent = '', $exclude_id = null )
  {
    $select = new Select('page');
    $select->addField('id_page');
    $select->addField('title');
    $arrCriteria = array(
        'is_del = ? ' => '0'
    );
    if ( $id_page_cat ) {
      $arrCriteria['id_page_cat = ?'] = $id_page_cat;
    }
    if ( $exclude_id ) {
      $arrCriteria['id_page <> ?'] = $exclude_id;
    }

    if ( $id_parent !== '' ) {
      if ( is_null($id_parent) ) {
        $arrCriteria['id_parent IS NULL'] = null;
      } else {
        $arrCriteria['id_parent = ?'] = $id_parent;
      }
    }
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_page']] = $row['title'];
    }

    return $arrOptions;
  }

  public function getById ( $id )
  {
    $row = parent::getById($id);
    $row['infinite'] = $this->loadInfinity($id);

    return $row;
  }

  public function loadInfinity ( $id )
  {
    $r = array();

    $first = parent::getById($id);
    $id_cat = $first['id_page_cat'];
    if ( $first['id_parent'] ) {

      $last = array(
          'dropdown' => $this->getListArray($id_cat, $first['id_parent'], $first['id_page']),
          'selected' => null
      );

      while ($first['id_parent']) {
        $second = parent::getById($first['id_parent']);
        $r[] = array(
            'dropdown' => $this->getListArray($id_cat, $second['id_parent']),
            'selected' => $first['id_parent']
        );

        $first = $second;
      }

      $r = array_reverse($r);

      $r[] = $last;
    }

    return $r;
  }

}

<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\File\MoveFiles;
use Din\Filters\Date\DateFormat;
use Din\Essential\Helpers\Form;
use Din\Filters\String\Html;
use Din\Essential\Helpers\Link;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;
use Exception;
use Din\Essential\Helpers\SequenceResult;
use Helpers\Arrays;

/**
 *
 * @package app.models
 */
class PageModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('page');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['cover'] = null;
      $table['uri'] = null;
    }

    $page_cat = new PageCatModel;
    $page_cat_dropdown = $page_cat->getListArray();

    $table['title'] = Html::scape($table['title']);
    $table['content'] = Form::Ck('content', $table['content']);
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
    $table['uri'] = Link::formatUri($table['uri'], false);
    $table['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $table['id_page_cat'], 'Selecione um Menu', null, 'ajax_intinify_cat');

    $table['id_parent'] = $this->loadInfinity(!$exclude_fields);
    $table['target'] = Form::Dropdown('target', Arrays::$target, $table['target']);

    $infinite_drop = array();
    foreach ( (array) $table['id_parent'] as $i => $drop ) {
      $addClass = 'other';
      $infinite_drop[] = $this->formatInfiniteDropdown($drop['dropdown'], $drop['selected'], $addClass);
    }

    $table['id_parent'] = $infinite_drop;

    return $table;

  }

  public function formatInfiniteDropdown ( $dropdown, $selected = null )
  {
    $dropdown = Form::Dropdown('id_parent[]', $dropdown, $selected, 'Subnível de Página', null, 'ajax_infinity');

    return $dropdown;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    if ( $this->_filters['id_page_cat'] != '' && $this->_filters['id_page_cat'] != '0' ) {
      $arrCriteria['a.id_page_cat = ?'] = $this->_filters['id_page_cat'];
    }

    $select = new Select('page');
    $select->addField('id_page');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->addField('url');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,a.title');

    $select->inner_join('id_page_cat', Select::construct('page_cat')
                    ->addField('title', 'menu'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq = new SequenceResult($this->_entity, $this->_dao);
    $result = $seq->filterResult($result, $arrCriteria);
    //

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if ( isset($row['sequence_list_array']) ) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page'], 'drop_sequence');
      }
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->arrayKeyExists(Arrays::$target)->validate('target', 'Target');
    $v->dbFk($this->_dao, 'page_cat')->validate('id_page_cat', 'Menu');
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_page');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('id_page_cat');
    $f->idParent()->filter('id_parent');
    $f->string()->filter('title');
    $f->string()->filter('content');
    $f->string()->filter('description');
    $f->string()->filter('keywords');
    $f->string()->filter('url');
    $f->string()->filter('target');
    $page_cat = new PageCatModel;
    $prefix = $page_cat->getTitle($this->_table->id_page_cat);
    $f->defaultUri('title', '', $prefix)->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/page/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //
    $mf->move();

    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->arrayKeyExists(Arrays::$target)->validate('target', 'Target');
    $v->dbFk($this->_dao, 'page_cat')->validate('id_page_cat', 'Menu');
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('id_page_cat');
    $f->idParent()->filter('id_parent');
    $f->string()->filter('title');
    $f->string()->filter('content');
    $f->string()->filter('description');
    $f->string()->filter('keywords');
    $f->string()->filter('url');
    $f->string()->filter('target');
    $page_cat = new PageCatModel;
    $prefix = $page_cat->getTitle($this->_table->id_page_cat);
    $f->defaultUri('title', '', $prefix)->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/page/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //
    $mf->move();

    $this->dao_update();

  }

  public function formatFilters ()
  {
    $page_cat = new PageCatModel;
    $page_cat_dropdown = $page_cat->getListArray();

    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['id_page_cat'] = Form::Dropdown('id_page_cat', $page_cat_dropdown, $this->_filters['id_page_cat'], 'Filtro por Menu');

    return $this->_filters;

  }

  public function getListArray ( $id_page_cat = null, $id_parent = '', $exclude_id = '' )
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
    if ( $exclude_id != '' ) {
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

  public function loadInfinity ( $exclude_self = true )
  {
    $r = array();

    try {
      $first = $this->getInfinityMembers();
    } catch (Exception $e) {
      return null;
    }

    $id_cat = $first['id_page_cat'];

    $exclude_id = $exclude_self ? $first['id_page'] : null;

    $r[] = array(
        'dropdown' => $this->getListArray($id_cat, $first['id_parent'], $exclude_id),
        'selected' => null
    );

    if ( $first['id_parent'] ) {

      while ($first['id_parent']) {
        $second = $this->getInfinityMembers($first['id_parent']);
        $r[] = array(
            'dropdown' => $this->getListArray($id_cat, $second['id_parent']),
            'selected' => $first['id_parent']
        );

        $first = $second;
      }

      $r = array_reverse($r);
    }

    return $r;

  }

  protected function getInfinityMembers ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        'id_page = ?' => $this->getId()
    );

    $select = new Select('page');
    $select->addField('id_page_cat');
    $select->addField('id_page');
    $select->addField('id_parent');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    return $result[0];

  }

  public function beforeDelete ( $tableHistory )
  {
    $id_page = $tableHistory['id_page'];

    $select = new Select($this->_table);
    $select->addField('id_page');
    $select->where(array(
        'id_parent = ?' => $id_page
    ));

    $result = $this->_dao->select($select);

    foreach ( $result as $row ) {
      $this->delete(array(array(
              'id' => $row['id_page']
      )));
    }

  }

}

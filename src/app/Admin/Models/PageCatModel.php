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
use Din\Essential\Helpers\SequenceResult;
use Helpers\Arrays;

/**
 *
 * @package app.models
 */
class PageCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('page_cat');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['cover'] = null;
      $table['uri'] = null;
      $table['url'] = null;
    }

    $table['title'] = Html::scape($table['title']);
    $table['content'] = Form::Ck('content', $table['content']);
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
    $table['uri'] = Link::formatUri($table['uri'], false);
    $table['target'] = Form::Dropdown('target', Arrays::$target, $table['target']);

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('is_active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->addField('url');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq = new SequenceResult($this->_entity, $this->_dao);
    $result = $seq->filterResult($result, $arrCriteria);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page_cat'], 'form-control drop_sequence');
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->arrayKeyExists(Arrays::$target)->validate('target', 'Target');
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_page_cat');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->string()->filter('content');
    $f->string()->filter('description');
    $f->string()->filter('keywords');
    $f->string()->filter('url');
    $f->string()->filter('target');
    $f->defaultUri('title')->filter('uri');
    $f->sequence($this->_dao, $this->_entity)->filter('sequence');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/page_cat/{$this->getId()}/cover", $has_cover
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
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->string()->filter('content');
    $f->string()->filter('description');
    $f->string()->filter('keywords');
    $f->string()->filter('url');
    $f->string()->filter('target');
    $f->defaultUri('title')->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/page_cat/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //
    $mf->move();

    $this->dao_update();

  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;

  }

  public function getListArray ()
  {
    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_page_cat']] = $row['title'];
    }

    return $arrOptions;

  }

  public function getTitle ( $id_page_cat )
  {

    $arrCriteria = array(
        'a.id_page_cat = ?' => $id_page_cat
    );

    $select = new Select('page_cat');
    $select->addField('title');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( count($result) ) {
      return $result[0]['title'];
    }

  }

}

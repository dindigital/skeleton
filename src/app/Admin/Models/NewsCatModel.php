<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\File\MoveFiles;
use Din\Filters\Date\DateFormat;
use Din\Essential\Helpers\Form;
use Helpers\Arrays;
use Din\Filters\String\Html;
use Din\Essential\Helpers\Link;
use Din\TableFilter\TableFilter;
use Din\Essential\Helpers\SequenceResult;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class NewsCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('news_cat');

  }

  protected function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['cover'] = null;
      $table['uri'] = null;
    }

    $table['title'] = Html::scape($table['title']);
    $table['cover_uploader'] = Form::Plupload('cover', $table['cover'], 'image');
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    if ( isset($this->_filters['is_home']) && $this->_filters['is_home'] == '1' ) {
      $arrCriteria['is_home = ?'] = '1';
    } elseif ( isset($this->_filters['is_home']) && $this->_filters['is_home'] == '2' ) {
      $arrCriteria['is_home = ?'] = '0';
    }

    $select = new Select('news_cat');
    $select->addField('id_news_cat');
    $select->addField('is_active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('sequence');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq_result = new SequenceResult($this->_entity, $this->_dao);
    $result = $seq_result->filterResult($result, $arrCriteria);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if ( isset($row['sequence_list_array']) ) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_news_cat'], 'drop_sequence');
      }
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_news_cat');
    $f->intval()->filter('is_active');
    $f->intval()->filter('is_home');
    $f->timestamp()->filter('inc_date');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId(), 'news')->filter('uri');
    $f->sequence($this->_dao, $this->_entity)->filter('sequence');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/news_cat/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //
    $mf->move();

    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $has_cover = $v->upload()->validate('cover', 'Capa');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->intval()->filter('is_home');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId(), 'news')->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/news_cat/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //
    $mf->move();
    //
    $this->dao_update();

  }

  public function getListArray ()
  {
    $select = new Select('news_cat');
    $select->addField('id_news_cat');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_news_cat']] = $row['title'];
    }

    return $arrOptions;

  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['is_home'] = Form::Dropdown('is_home', Arrays::$simNao, $this->_filters['is_home'], 'Home?');

    return $this->_filters;

  }

}

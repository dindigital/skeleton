<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use Din\Essential\Helpers\Form;
use Din\Filters\String\Html;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;

/**
 *
 * @package app.models
 */
class NewsSubModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('news_sub');

  }

  protected function formatTable ( $table, $exclude_fields = false )
  {

    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $table['title'] = Html::scape($table['title']);
    $table['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $table['id_news_cat'], 'Selecione uma Categoria');

    return $table;

  }

  public function getList ()
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    if ( $this->_filters['id_news_cat'] != '' && $this->_filters['id_news_cat'] != '0' ) {
      $arrCriteria['a.id_news_cat = ?'] = $this->_filters['id_news_cat'];
    }

    $select = new Select('news_sub');
    $select->addField('id_news_sub');
    $select->addField('id_news_cat');
    $select->addField('is_active');
    $select->addField('title');
    $select->where($arrCriteria);
    $select->order_by('title');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->dbFk($this->_dao, 'news_cat')->validate('id_news_cat', 'Categoria');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_news_sub');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('id_news_cat');
    $f->string()->filter('title');
    //

    $this->dao_insert();

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->dbFk($this->_dao, 'news_cat')->validate('id_news_cat', 'Categoria');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->string()->filter('id_news_cat');
    $f->string()->filter('title');
    //

    $this->dao_update();

  }

  public function formatFilters ()
  {
    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $this->_filters['id_news_cat'], 'Filtro por Categoria');

    return $this->_filters;

  }

  public function getListArray ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ? ' => '0'
    );

    if ( array_key_exists('id_news_cat', $arrFilters) && $arrFilters['id_news_cat'] != '0' && $arrFilters['id_news_cat'] != '' ) {
      $arrCriteria['id_news_cat = ?'] = $arrFilters['id_news_cat'];
    }

    $select = new Select('news_sub');
    $select->addField('id_news_sub');
    $select->addField('title');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_news_sub']] = $row['title'];
    }

    return $arrOptions;

  }

  public function formatDropdown ( $dropdown, $selected = null )
  {
    return Form::Dropdown('id_news_sub', $dropdown, $selected, 'Selecione a Subcategoria', null, 'select2');

  }

}

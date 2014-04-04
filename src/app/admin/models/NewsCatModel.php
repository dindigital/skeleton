<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\Exception\JsonException;
use src\app\admin\helpers\SequenceResult;

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
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
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
    $select->addField('active');
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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_news_cat');
    $filter->setIntval('active');
    $filter->setIntval('is_home');
    $filter->setTimestamp('inc_date');
    $filter->setString('title');
    $filter->setDefaultUri('title', $this->getId(), 'news');
    $filter->setSequence('sequence', $this->_dao, $this->_entity);
    //
    $mf = new MoveFiles;
    $filter->setUploaded('cover', "/system/uploads/news_cat/{$this->getId()}/cover", $has_cover, $mf);
    //
    $mf->move();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setIntval('active');
    $filter->setIntval('is_home');
    $filter->setString('title');
    $filter->setDefaultUri('title', $this->getId(), 'news', 'uri');
    //
    $mf = new MoveFiles;
    $filter->setUploaded('cover', "/system/uploads/news_cat/{$this->getId()}/cover", $has_cover, $mf);
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

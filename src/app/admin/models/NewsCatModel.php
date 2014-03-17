<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\SequenceModel;
use src\app\admin\helpers\MoveFiles;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Arrays;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

/**
 *
 * @package app.models
 */
class NewsCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('news_cat');
  }

  protected function formatTable ( $table )
  {
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

    if (isset($this->_filters['is_home']) && $this->_filters['is_home'] == '1') {
      $arrCriteria['is_home = ?'] = '1';
    } elseif (isset($this->_filters['is_home']) && $this->_filters['is_home'] == '2') {
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

    $seq = new SequenceModel($this);
    $result = $seq->setListArray($result, $arrCriteria);

    foreach ( $result as $i => $row ) {
      $result[$i]['inc_date'] = DateFormat::filter_date($row['inc_date']);
      if (isset($row['sequence_list_array'])) {
        $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_news_cat'], 'drop_sequence');
      }
    }

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setIntval('active', $input['active']);
    $this->setIntval('is_home', $input['is_home']);
    $this->setTimestamp('inc_date');
    $this->setDefaultUri($input['title'], 'news');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    $seq = new SequenceModel($this);
    $seq->setSequence();

    $mf->move();

    $this->dao_insert($input);
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setIntval('is_home', $input['is_home']);
    $this->setDefaultUri($input['title'], 'news', $input['uri']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_update($input);
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

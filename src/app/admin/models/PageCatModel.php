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
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;

/**
 *
 * @package app.models
 */
class PageCatModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('page_cat');
  }

  public function formatTable ( $table )
  {
    $table['title'] = Html::scape($table['title']);
    $table['content'] = Form::Ck('content', $table['content']);
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

    $select = new Select('page_cat');
    $select->addField('id_page_cat');
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
      $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_page_cat'], 'form-control drop_sequence');
    }

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setIntval('active', $input['active']);
    $this->setTimestamp('inc_date');
    $this->setDefaultUri($input['title'], 'page');
    $this->_table->content = $input['content'];
    $this->_table->description = $input['description'];
    $this->_table->keywords = $input['keywords'];

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    $seq = new SequenceModel($this);
    $seq->setSequence();

    $mf->move();

    $this->dao_insert();
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'page', $input['uri']);
    $this->_table->content = $input['content'];
    $this->_table->description = $input['description'];
    $this->_table->keywords = $input['keywords'];

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setRequiredString('title', 'Título');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    // deleta o arquivo antigo caso exista e tenha upload novo
    $row = $this->getById();
    if ( $this->_table->cover && $row['cover'] ) {
      $destiny = 'public/' . $row['cover'];
      @unlink($destiny);
    }


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

}

<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\RelationshipModel;
use src\app\admin\models\essential\Facepostable;
use Din\Filters\Date\DateFormat;
use src\app\admin\helpers\Form;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use Din\Image\Picuri;
use Din\DataAccessLayer\Table\Table;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\validators\DBValidator;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\Exception\JsonException;
use src\app\admin\helpers\SequenceResult;

/**
 *
 * @package app.models
 */
class NewsModel extends BaseModelAdm implements Facepostable
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('news');
  }

  protected function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['cover'] = null;
      $table['uri'] = null;
    }

    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $table['title'] = Html::scape($table['title']);
    $table['date'] = isset($table['date']) ? DateFormat::filter_date($table['date']) : date('d/m/Y');
    $table['head'] = Html::scape($table['head']);
    $table['body'] = Form::Ck('body', $table['body']);
    $table['uri'] = Link::formatUri($table['uri']);
    $table['cover_uploader'] = Form::Upload('cover', $table['cover'], 'image');
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

    $select = new Select('news');
    $select->addField('id_news');
    $select->addField('id_news_cat');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->addField('sequence');
    $select->addField('uri');
    $select->addField('has_tweet');
    $select->addField('has_facepost');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,date DESC');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq_result = new SequenceResult($this->_entity, $this->_dao);
    $result = $seq_result->filterResult($result, $arrCriteria);

    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
      $result[$i]['sequence'] = Form::Dropdown('sequence', $row['sequence_list_array'], $row['sequence'], '', $row['id_news'], 'form-control drop_sequence');
    }

    return $result;
  }

  public function insert ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    $str_validator->validateRequiredDate('date', 'Data');
    $str_validator->validateRequiredString('body', 'Corpo');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'news');
    $db_validator->validateFk('id_news_cat', 'Categoria', 'news_cat');
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_news');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('id_news_cat');
    $f->string()->filter('title');
    $f->date()->filter('date');
    $f->string()->filter('head');
    $f->string()->filter('body');
    $f->defaultUri('title', $this->getId(), 'noticias')->filter('uri');
    $f->sequence($this->_dao, $this->_entity)->filter('sequence');
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/news/{$this->getId()}/cover", $has_cover
            , $mf)->filter('cover');
    //

    $this->dao_insert();

    $mf->move();

    $this->relationship('photo', $input['photo']);
    $this->relationship('video', $input['video']);
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    $str_validator->validateRequiredDate('date', 'Data');
    $str_validator->validateRequiredString('body', 'Corpo');
    //
    $db_validator = new DBValidator($input, $this->_dao, 'news');
    $db_validator->validateFk('id_news_cat', 'Categoria', 'news_cat');
    //
    $upl_validator = new UploadValidator($input);
    $has_cover = $upl_validator->validateFile('cover');
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('id_news_cat');
    $f->string()->filter('title');
    $f->date()->filter('date');
    $f->string()->filter('head');
    $f->string()->filter('body');
    $f->defaultUri('title', $this->getId(), 'noticias')->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/news/{$this->getId()}/cover"
            , $has_cover, $mf)->filter('cover');
    //
    $mf->move();

    $this->dao_update();

    $this->relationship('photo', $input['photo']);
    $this->relationship('video', $input['video']);

    $cache = new essential\CacheModel();
    $cache->delete($this->_table->uri);
  }

  private function relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('news');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->smartInsert($this->getId(), $array);
  }

  public function generatePost ()
  {
    $select = new Select('news');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('cover');
    $select->addField('head');
    $select->where(array(
        'id_news = ?' => $this->getId()
    ));

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado');

    $post = array(
        'name' => $result[0]['title'],
        'message' => $result[0]['head'],
        'link' => URL . $result[0]['uri'],
        'description' => $result[0]['head'],
    );

    if ( $result[0]['cover'] ) {
      $post['picture'] = URL . Picuri::picUri($result[0]['cover'], 400, 400, false, array(), 'path');
    }

    return $post;
  }

  public function sentPost ( $id_facepost )
  {
    $this->_table->has_facepost = '1';
    $this->_dao->update($this->_table, array('id_news = ?' => $this->getId()));

    //_# INSERE RELACAO
    $table = new Table('r_news_facepost');
    $table->id_news = $this->getId();
    $table->id_facepost = $id_facepost;
    $this->_dao->insert($table);
  }

  public function getPosts ()
  {
    $select = new Select('facepost');
    $select->addField('date');
    $select->addField('name');
    $select->addField('link');
    $select->addField('picture');
    $select->addField('description');
    $select->addField('message');

    $select->inner_join('id_facepost', Select::construct('r_news_facepost'));

    $select->where(array(
        'b.id_news = ?' => $this->getId()
    ));

    $select->order_by('date DESC');

    $result = $this->_dao->select($select);

    return $result;
  }

  public function formatFilters ()
  {
    $news_cat = new NewsCatModel;
    $news_cat_dropdown = $news_cat->getListArray();

    $this->_filters['title'] = Html::scape($this->_filters['title']);
    $this->_filters['id_news_cat'] = Form::Dropdown('id_news_cat', $news_cat_dropdown, $this->_filters['id_news_cat'], 'Filtro por Categoria');

    return $this->_filters;
  }

}

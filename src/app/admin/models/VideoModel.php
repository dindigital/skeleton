<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\RelationshipModel;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\custom_filter\TableFilterAdm as TableFilter;
use Din\Exception\JsonException;
use src\app\admin\models\essential\YouTubeModel;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\MoveFiles;
use Din\File\Folder;

/**
 *
 * @package app.models
 */
class VideoModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('video');
  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['file'] = null;
      $table['id_youtube'] = null;
      $table['uri'] = null;
    }

    $youTubeModel = new YouTubeModel();
    $youTubeModel->getYouTubeLogin();

    if ( is_null($table['date']) ) {
      $table['date'] = date('Y-m-d');
    }

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $table['uri'] = Link::formatUri($table['uri']);
    $table['file_uploader'] = Form::Upload('file', $table['file'], 'video');

    return $table;
  }

  public function onGetById ( Select $select )
  {

    $select->addFField('has_youtube', 'IF (id_youtube IS NOT NULL, 1, 0)');
  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('video');
    $select->addField('id_video');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result;
  }

  public function insert ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', "Título");
    $str_validator->validateRequiredString('description', "Descrição");
    $str_validator->validateRequiredDate('date', "Data");
    //
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_video');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->string()->filter('description');
    $f->string()->filter('link_youtube');
    $f->string()->filter('link_vimeo');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId())->filter('uri');

    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/video/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->dao_insert();

    $this->relationship('tag', $input['tag']);

    if ( $input['publish_youtube'] == '1' ) {
      $this->save_youtube();
    }
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', "Título");
    $str_validator->validateRequiredString('description', "Descrição");
    $str_validator->validateRequiredDate('date', "Data");
    //
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    //
    JsonException::throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->string()->filter('description');
    $f->string()->filter('link_youtube');
    $f->string()->filter('link_vimeo');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId())->filter('uri');

    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/video/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->dao_update();

    $this->relationship('tag', $input['tag']);

    if ( $input['publish_youtube'] == '1' ) {
      $this->save_youtube();
    } else if ( $input['republish_youtube'] == '1' ) {
      $this->save_youtube(true);
    }
  }

  private function relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('video');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->insert($this->getId(), $array);
  }

  private function save_youtube ( $delete = false )
  {

    $youTubeModel = new YouTubeModel;

    $row = $this->getById();

    if ( !$file = $this->_table->file ) {
      $file = $row['file'];
    }

    if ( $delete && !is_null($row['id_youtube']) ) {
      $youTubeModel->delete($row['id_youtube']);
    }

    $id_youtube = $youTubeModel->insert($row);

    if ( $id_youtube ) {

      $input = array(
          'id_youtube' => $id_youtube
      );

      $f = new TableFilter($this->_table, $input);
      $f->string()->filter('id_youtube');
      $this->dao_update();
    }
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    return $this->_filters;
  }

  public function delete ( $itens )
  {
    foreach ( $itens as $item ) {
      $tableHistory = $this->getById($item['id'], false);
      $this->deleteChildren($this->_entity, $item['id']);

      Folder::delete("public/system/uploads/video/{$item['id']}");
      $this->_dao->delete('video', array('id_video = ?' => $item['id']));
      $this->log('D', $tableHistory['title'], $this->_table, $tableHistory);
      // DELETE YOUTUBE //mostra ai o erro
      $youTubeModel = new YouTubeModel;
      $youTubeModel->delete($tableHistory['id_youtube']);
      //
    }
  }

}

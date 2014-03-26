<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\validators\StringValidator;
use src\app\admin\helpers\TableFilter;
use Din\Exception\JsonException;
use src\app\admin\helpers\Form;
use src\app\admin\validators\UploadValidator;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\SoundCloudModel;

/**
 *
 * @package app.models
 */
class AudioModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('audio');
  }

  public function formatTable ( $table )
  {

    if ( is_null($table['date']) ) {
      $table['date'] = date('Y-m-d');
    }

    $table['file_uploader'] = Form::Upload('file', $table['file'], 'audio');

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;
  }

  public function getList ()
  {

    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('audio');
    $select->addField('id_audio');
    $select->addField('active');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');

    $select->left_join('id_soundcloud', Select::construct('soundcloud')
                    ->addFField('has_sc', 'IF (b.id_soundcloud, 1, 0)')
                    ->addField('link', 'soundcloud_link'));

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

  public function getRow ( $id = null )
  {

    $sc = new SoundCloudModel;
    $sc->getSoundCloudLogin();

    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        'a.id_audio = ?' => $this->getId()
    );

    $select = new Select('audio');
    $select->addAllFields();

    $select->left_join('id_soundcloud', Select::construct('soundcloud')
                    ->addFField('has_sc', 'IF (b.id_soundcloud IS NOT NULL, 1, 0)')
                    ->addField('link', 'soundcloud_link')
    );

    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $this->formatTable($result[0]);

    return $row;
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
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_audio');
    $filter->setTimestamp('inc_date');
    $filter->setIntval('active');
    $filter->setString('title');
    $filter->setString('description');
    $filter->setDate('date');
    $filter->setDefaultUri('title', $this->getId());

    $mf = new MoveFiles;
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/audio/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
    $mf->move();

    $this->dao_insert();

    if ( $input['publish_sc'] == '1' ) {
      $this->save_soundcloud();
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
    $filter = new TableFilter($this->_table, $input);
    $filter->setIntval('active');
    $filter->setString('title');
    $filter->setString('description');
    $filter->setDate('date');
    $filter->setDefaultUri('title', $this->getId());

    //
    $mf = new MoveFiles;
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/audio/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
    $mf->move();
    $this->dao_update();

    if ( $input['publish_sc'] == '1' ) {
      $this->save_soundcloud();
    }
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    return $this->_filters;
  }

  protected function save_soundcloud ( $delete_previous = false )
  {

    $row = $this->getById();

    // arquivo que acabou de subir ou arquivo previamente gravado
    if ( !$file = $this->_table->file ) {
      $file = $row['file'];
    }

    $previous_id = $row['id_soundcloud'];

    if ( $file ) {
      $title = $this->_table->title;

      $pathinfo = pathinfo($file);
      if ( 'mp3' != $pathinfo['extension'] ) {
        throw new Exception('Upload no SoundCloud é restringido a arquivos MP3');
      }

      $soundcloud_model = new SoundCloudModel;
      $soundcloud_model->insertComplete(array(
          'file' => $_SERVER['DOCUMENT_ROOT'] . $file,
          'title' => $title,
      ));
    }
  }

}

<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use src\app\admin\helpers\Link;
use src\app\admin\validators\StringValidator;
use Din\Exception\JsonException;
use src\app\admin\helpers\Form;
use src\app\admin\validators\UploadValidator;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\SoundCloudModel;
use src\app\admin\filters\TableFilter;

/**
 *
 * @package app.models
 */
class AudioModel extends BaseModelAdm
{

  protected $_sc;

  protected function setSoundCloud ()
  {
    if ( is_null($this->_sc) ) {
      $this->_sc = new SoundCloudModel;
      $this->_sc->makeLogin();
    }
  }

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('audio');
  }

  public function formatTable ( $table )
  {
    $this->setSoundCloud();

    if ( is_null($table['date']) ) {
      $table['date'] = date('Y-m-d');
    }

    $table['file_uploader'] = Form::Upload('file', $table['file'], 'audio');

    $table['title'] = Html::scape($table['title']);
    $table['date'] = DateFormat::filter_date($table['date']);
    $table['uri'] = Link::formatUri($table['uri']);

    if ( isset($table['soundcloud_link']) ) {
      $table['soundcloud_html'] = '<iframe width="80%" height="300" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?visual=true&url=https%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F' . $table['track_id'] . '&show_artwork=true"></iframe>';
    }

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
                    ->addField('track_permalink', 'soundcloud_link'));

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
  
  public function onGetById ( Select $select )
  {
      
    $select->left_join('id_soundcloud', Select::construct('soundcloud')
                    ->addFField('has_sc', 'IF (b.id_soundcloud IS NOT NULL, 1, 0)')
                    ->addField('track_id')
                    ->addField('track_permalink', 'soundcloud_link')
    );
    
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
    } else if ( $input['republish_sc'] == '1' ) {
      $this->save_soundcloud(true);
    }
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);
    return $this->_filters;
  }

  protected function save_soundcloud ( $delete_previous = false )
  {
    $id_audio = $this->getId();
    $title = $this->_table->title;

    $row = $this->getById($id_audio);

    // arquivo que acabou de subir ou arquivo previamente gravado
    if ( !$file = $this->_table->file ) {
      $file = $row['file'];
    }

    if ( $file ) {
      $this->setSoundCloud();

      //delete previous
      if ( $delete_previous ) {
        $this->setTable('audio');
        $this->_table->id_soundcloud = null;
        $this->_dao->update($this->_table, array(
            'id_audio = ?' => $id_audio
        ));

        $this->_sc->deletePrevious($row['id_soundcloud']);
      }

      //insert new
      $id_soundcloud = $this->_sc->insertComplete(array(
          'file' => 'public' . $file,
          'title' => $title,
      ));

      //
      $this->setTable('audio');
      $this->_table->id_soundcloud = $id_soundcloud;
      $this->_table->has_sc = '1';
      $this->_dao->update($this->_table, array(
          'id_audio = ?' => $id_audio
      ));
    }

    $this->_table->id_audio = $id_audio; //pra nao bugar no redirecionamento
  }

  public function beforeDelete ( $tableHistory )
  {
    //delete soundcloud
    $this->setSoundCloud();

    $this->setTable('audio');
    $this->_table->id_soundcloud = null;
    $this->_dao->update($this->_table, array(
        'id_audio = ?' => $tableHistory['id_audio']
    ));

    $this->_sc->deletePrevious($tableHistory['id_soundcloud']);
    //
  }

}

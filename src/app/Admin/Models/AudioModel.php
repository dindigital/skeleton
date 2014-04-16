<?php

namespace Admin\Models;

use Din\Essential\Models\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Din\Essential\Helpers\PaginatorAdmin;
use Din\Filters\Date\DateFormat;
use Din\Filters\String\Html;
use Din\Essential\Helpers\Link;
use Din\Essential\Helpers\Form;
use Din\File\MoveFiles;
use Din\Essential\Models\SoundCloudModel;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;

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

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['file'] = null;
      $table['has_sc'] = 0;
      $table['uri'] = null;
    }

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
    $select->addField('is_active');
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
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->stringLenght(10, 156)->validate('description', 'Descrição');
    $v->date()->validate('date', 'Data');
    $has_file = $v->upload()->validate('file', 'Arquivo de Áudio');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_audio');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->string()->filter('description');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId())->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/audio/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->dao_insert();

    if ( $input['publish_sc'] == '1' ) {
      $this->save_soundcloud();
    }

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $v->stringLenght(10, 156)->validate('description', 'Descrição');
    $v->date()->validate('date', 'Data');
    $has_file = $v->upload()->validate('file', 'Arquivo de Áudio');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('is_active');
    $f->string()->filter('title');
    $f->string()->filter('description');
    $f->date()->filter('date');
    $f->defaultUri('title', $this->getId())->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/audio/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
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

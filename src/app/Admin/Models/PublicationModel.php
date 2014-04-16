<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use Admin\Helpers\PaginatorAdmin;
use Admin\Helpers\MoveFiles;
use Din\Filters\String\Html;
use Admin\Helpers\Form;
use Admin\Helpers\Link;
use Admin\Models\Essential\IssuuModel;
use Din\TableFilter\TableFilter;
use Din\InputValidator\InputValidator;
use Din\Filters\String\Uri;
use Din\Filters\String\LimitChars;
use Admin\Helpers\Embed;

/**
 *
 * @package app.models
 */
class PublicationModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('publication');

  }

  public function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      $table['file'] = null;
      $table['has_issuu'] = 0;
      $table['uri'] = null;
    }

    $table['title'] = Html::scape($table['title']);
    $table['file_uploader'] = Form::Upload('file', $table['file'], 'document');
    $table['uri'] = Link::formatUri($table['uri']);

    if ( $table['has_issuu'] ) {
      $table['issuu_embed'] = Embed::issuu($table['issuu_document_id'], 500, 400);
    }

    return $table;

  }

  public function onGetById ( Select $select )
  {

    $select->left_join('id_issuu', Select::construct('issuu')
                    ->addFField('has_issuu', 'IF (b.id_issuu IS NOT NULL, 1, 0)')
                    ->addField('link', 'issuu_link')
                    ->addField('document_id', 'issuu_document_id')
    );

  }

  public function getList ()
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $this->_filters['title'] . '%'
    );

    $select = new Select('publication');
    $select->addField('id_publication');
    $select->addField('active');
    $select->addField('title');

    $select->left_join('id_issuu', Select::construct('issuu')
                    ->addFField('has_issuu', 'IF (b.id_issuu, 1, 0)')
                    ->addField('link', 'issuu_link'));

    $select->where($arrCriteria);
    $select->order_by('title');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $this->_filters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['title'] = Html::scape($row['title']);
    }

    return $result;

  }

  public function insert ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $has_file = $v->upload()->validate('file', 'Arquivo');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->newId()->filter('id_publication');
    $f->timestamp()->filter('inc_date');
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId())->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/publication/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->dao_insert();

    $this->afterSave($input);

  }

  public function update ( $input )
  {
    $v = new InputValidator($input);
    $v->string()->validate('title', 'Título');
    $has_file = $v->upload()->validate('file', 'Arquivo');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->intval()->filter('active');
    $f->string()->filter('title');
    $f->defaultUri('title', $this->getId())->filter('uri');
    //
    $mf = new MoveFiles;
    $f->uploaded("/system/uploads/publication/{$this->getId()}/file", $has_file
            , $mf)->filter('file');
    //
    $mf->move();

    $this->dao_update();

    $this->afterSave($input);

  }

  public function afterSave ( $input )
  {
    // publicar no issuu
    if ( $input['republish_issuu'] == '1' || $input['publish_issuu'] == '1' ) {

      $row = $this->getById();

      $delete_previous = $input['republish_issuu'] == '1';
      $id_publication = $this->getId();
      $file = $this->_table->file;
      $title = $this->_table->title;
      $id_issuu = $row['id_issuu'];

      // arquivo que acabou de subir ou arquivo previamente gravado
      if ( !$file ) {
        $file = $row['file'];
      }

      if ( $file ) {
        $url = URL . $file;

        $filename = Uri::format(LimitChars::filter($title, 20, ''));
        $filename .= substr(uniqid(), 0, 5) . '.' . strtolower(pathinfo($file, PATHINFO_EXTENSION));

        $issuu_model = new IssuuModel;
        $issuu_model->insertComplete(array(
            'url' => $url,
            'name' => $filename,
            'title' => $title,
            'parent_table' => 'publication',
            'parent_id_field' => 'id_publication',
            'parent_id_value' => $id_publication,
            'id_issuu' => $id_issuu,
            'delete_previous' => $delete_previous
        ));
      }
    }

  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;

  }

  public function beforeDelete ( $tableHistory )
  {
    $issuu = new IssuuModel;
    $issuu->deleteComplete(array(
        'id_issuu' => $tableHistory['id_issuu'],
        'parent_table' => 'publication',
        'parent_id_field' => 'id_publication',
        'parent_id_value' => $tableHistory['id_publication']
    ));

  }

}

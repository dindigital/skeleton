<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use Din\Filters\String\Html;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Link;
use Din\File\Folder;
use src\app\admin\models\essential\IssuuModel;
use src\app\admin\validators\StringValidator;
use src\app\admin\validators\UploadValidator;
use src\app\admin\filters\TableFilter;
use Din\Exception\JsonException;
use Din\Filters\String\Uri;
use Exception;
use Din\Filters\String\LimitChars;

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

  public function formatTable ( $table )
  {
    $table['title'] = Html::scape($table['title']);
    $table['file_uploader'] = Form::Upload('file', $table['file'], 'document');
    $table['uri'] = Link::formatUri($table['uri']);

    if ( $table['has_issuu'] ) {
      $issuu_embed = new essential\IssuuEmbedModel;
      $table['issuu_embed'] = $issuu_embed->get('publication_save_' . $table['id_publication'], $table['id_issuu'], $table['issuu_document_id'], '400', '400');
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
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    //
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setNewId('id_publication');
    $filter->setTimestamp('inc_date');
    $filter->setIntval('active');
    $filter->setString('title');
    $filter->setDefaultUri('title', $this->getId());
    //
    $mf = new MoveFiles;
    $filter->setUploaded('file', "/system/uploads/publication/{$this->getId()}/file", $has_file, $mf);
    //
    $mf->move();

    $this->dao_insert();

    $this->afterSave($input);
  }

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('title', 'Título');
    //
    $upl_validator = new UploadValidator($input);
    $has_file = $upl_validator->validateFile('file');
    //
    JsonException::throwException();
    //
    $filter = new TableFilter($this->_table, $input);
    $filter->setIntval('active');
    $filter->setString('title');
    $filter->setDefaultUri('title', $this->getId());
    //
    $mf = new MoveFiles;
    $filter->setUploaded('file', "/system/uploads/publication/{$this->getId()}/file", $has_file, $mf);
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

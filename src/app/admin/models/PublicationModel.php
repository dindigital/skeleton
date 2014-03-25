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

  public function getById ( $id = null )
  {
    if ( $id ) {
      $this->setId($id);
    }

    $arrCriteria = array(
        'a.id_publication = ?' => $this->getId()
    );

    $select = new Select('publication');
    $select->addAllFields();

    $select->left_join('id_issuu', Select::construct('issuu')
                    ->addFField('has_issuu', 'IF (b.id_issuu IS NOT NULL, 1, 0)')
                    ->addField('link', 'issuu_link')
                    ->addField('document_id', 'issuu_document_id')
    );

    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) )
      throw new Exception('Registro não encontrado.');

    $row = $this->formatTable($result[0]);

    return $row;
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
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/publication/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
    $mf->move();

    $this->dao_insert();

    if ( $input['publish_issuu'] == '1' ) {
      $this->save_issuu();
    }
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
    if ( $has_file ) {
      $filter->setUploaded('file', "/system/uploads/publication/{$this->getId()}/file");
      $mf->addFile($input['file'][0]['tmp_name'], $this->_table->file);
    }
    $mf->move();

    $this->dao_update();

    if ( $input['publish_issuu'] == '1' ) {
      $this->save_issuu();
    } else if ( $input['republish_issuu'] == '1' ) {
      $this->save_issuu(true);
    }
  }

  public function save_issuu ( $delete_previous = false )
  {
    $row = $this->getById();

    // arquivo que acabou de subir ou arquivo previamente gravado
    if ( !$file = $this->_table->file ) {
      $file = $row['file'];
    }

    $previous_id = $row['id_issuu'];

    if ( $file ) {
      // prepara campos
      $url = URL . $file;
      $title = $this->_table->title;

      $pathinfo = pathinfo($file);
      if ( !in_array(strtolower($pathinfo['extension']), array('pdf', 'doc', 'docx')) )
        throw new Exception('Upload no Issuu é restringido a arquivos PDF ou DOC');

      $name = Uri::format(LimitChars::filter($this->_table->title, 20, ''));
      $name = substr($name, 0, 30) . uniqid() . '.' . strtolower($pathinfo['extension']);

      $issuu_model = new IssuuModel;
      $issuu_model->insertComplete(array(
          'url' => $url,
          'name' => $name,
          'title' => $title,
          'previous_id' => $delete_previous ? $previous_id : null,
          'parent_table' => 'publication',
          'parent_id_field' => 'id_publication',
          'parent_id_value' => $this->getId(),
      ));
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

      $this->deleteChildren('publication', $item['id']);

      Folder::delete("public/system/uploads/publication/{$item['id']}");
      $this->_dao->delete('publication', array('id_publication = ?' => $item['id']));
      $this->log('D', $tableHistory['title'], 'publication', $tableHistory);
      // DELETE ISSUU
      $issuu = new IssuuModel;
      $issuu->setId($tableHistory['id_issuu']);
      $issuu->deleteComplete();
      //
    }
  }

}

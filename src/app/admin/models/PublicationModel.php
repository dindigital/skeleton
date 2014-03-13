<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;
use Din\Filters\String\Html;
use src\app\admin\helpers\Form;
use src\app\admin\helpers\Link;
use Din\File\Folder;
use src\app\admin\models\essential\IssuuModel;

/**
 *
 * @package app.models
 */
class PublicationModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('publication');
  }

  public function formatTable ( $table )
  {
    $table['title'] = Html::scape($table['title']);
    $table['file_uploader'] = Form::Upload('file', $table['file'], 'document');
    $table['uri'] = Link::formatUri($table['uri']);

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
                    ->addField('link', 'issuu_link'));

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
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'publicacoes');

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setRequiredString('title', 'Título');
    $mf = new MoveFiles;
    $validator->setFile('file', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_insert();

    if ( $input['publish_issuu'] == '1' ) {
      $this->save_issuu();
    }
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], 'publicacoes', $input['uri']);

    $validator = new validator($this->_table);
    $validator->setInput($input);
    $validator->setId($this->getId());
    $validator->setRequiredString('title', 'Título');
    $mf = new MoveFiles;
    $validator->setFile('file', $mf);
    $validator->throwException();

    if ( $input['publish_issuu'] == '1' ) {
      $this->_table->has_issuu = '1';
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
      $name = basename($file);
      $title = $this->_table->title;

      $issuu_model = new IssuuModel;
      $issuu_model->insertComplete(array(
          'url' => $url,
          'name' => $name,
          'title' => $title,
          'id' => $this->getId(),
          'previous_id' => $delete_previous ? $previous_id : null
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
      $tableHistory = $this->getById($item['id']);

      // DELETE ISSUU
      $issuu = new IssuuModel;
      $issuu->setId($tableHistory['id_issuu']);
      $issuu->deleteComplete();

      $this->deleteChildren('publication', $item['id']);

      Folder::delete("public/system/uploads/publication/{$item['id']}");
      $this->_dao->delete('publication', array('id_publication = ?' => $item['id']));
      $this->log('D', $tableHistory['title'], 'publication', $tableHistory);
    }
  }

}

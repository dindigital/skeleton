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
    $table['file'] = Form::Upload('file', $table['file'], 'document');
    $table['uri'] = Link::formatUri($table['uri']);

    return $table;
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
    $select->addField('has_issuu');
    $select->addField('title');
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

    $mf->move();

    $this->dao_update();
    /*
      if ( !$file = $this->_table->file ) {
      $row = $this->getById();
      $file = $row['file'];
      }

      if ( $file ) {
      $api_key = 'xjfjs9fdjsc5yokt2otmwz7ua49kjovv';
      $api_secret = 'y3y5lbazcig8w7v90oj3lj9gxpru3d2u';

      $url = URL . $file;
      $name = 'name1';
      $title = 'title1';

      $issu = new \src\app\admin\helpers\issuu\Issuu($api_key, $api_secret);
      $r = $issu->document_url_upload($url, $name, $title);

      var_dump($r);
      exit;
      }
     */
  }

  public function formatFilters ()
  {
    $this->_filters['title'] = Html::scape($this->_filters['title']);

    return $this->_filters;
  }

}

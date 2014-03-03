<?php

namespace src\app\admin\models;

use src\app\admin\validators\BaseValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\models\essential\SequenceModel;
use src\app\admin\helpers\MoveFiles;
use src\app\admin\models\essential\RelationshipModel;

/**
 *
 * @package app.models
 */
class NewsModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('news');
  }

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );
    if ( $arrFilters['id_news_cat'] != '' && $arrFilters['id_news_cat'] != '0' ) {
      $arrCriteria['a.id_news_cat = ?'] = $arrFilters['id_news_cat'];
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
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,date DESC');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    $seq = new SequenceModel($this);
    $result = $seq->setListArray($result, $arrCriteria);

    return $result;
  }

  public function insert ( $input )
  {
    $this->setNewId();
    $this->setIntval('active', $input['active']);
    $this->setTimestamp('inc_date');
    $this->setDefaultUri($input['title']);
    $this->_table->head = $input['head'];
    $this->_table->body = $input['body'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setFk('id_news_cat', 'Categoria', 'news_cat');
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    $seq = new SequenceModel($this);
    $seq->setSequence();

    $mf->move();

    $this->dao_insert($input);

    $this->relationship('photo', $input['photo']);
    $this->relationship('video', $input['video']);
  }

  public function update ( $input )
  {
    $this->setIntval('active', $input['active']);
    $this->setDefaultUri($input['title'], '', $input['uri']);
    $this->_table->head = $input['head'];
    $this->_table->body = $input['body'];

    $validator = new validator($this->_table);
    $validator->setDao($this->_dao);
    $validator->setInput($input);
    $validator->setFk('id_news_cat', 'Categoria', 'news_cat');
    $validator->setRequiredString('title', 'Título');
    $validator->setRequiredDate('date', 'Data');

    $mf = new MoveFiles;
    $validator->setFile('cover', $mf);
    $validator->throwException();

    $mf->move();

    $this->dao_update($input);

    $this->relationship('photo', $input['photo']);
    $this->relationship('video', $input['video']);
  }

  private function relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentEntity('news');
    $relationshipModel->setForeignEntity($tbl);
    $relationshipModel->smartInsert($this->getId(), $array);
  }

}

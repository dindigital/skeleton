<?php

namespace src\app\admin\models;

use src\app\admin\validators\NewsValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;
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
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,date DESC');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);
    $result = Sequence::setListArray($this, $result, $arrCriteria);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $validator = new validator($this->_table);
    $validator->setIdNewsCat($info['id_news_cat']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setHead($info['head']);
    $validator->setBody($info['body']);
    $validator->setDefaultUri($info['title'], $this->getId());
    Sequence::setSequence($this, $validator);
    $validator->setIncDate();
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);

    $this->relationship('photo', $info['photo']);
    $this->relationship('video', $info['video']);
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setIdNewsCat($info['id_news_cat']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setHead($info['head']);
    $validator->setBody($info['body']);
    $validator->setDefaultUri($info['title'], $this->getId(), '', $info['uri']);
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_news = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);

    $this->relationship('photo', $info['photo']);
    $this->relationship('video', $info['video']);
  }

  private function relationship ( $tbl, $array )
  {
    $relationshipModel = new RelationshipModel();
    $relationshipModel->setCurrentSection('news');
    $relationshipModel->setRelationshipSection($tbl);
    $relationshipModel->insert2($this->getId(), $array);
  }

}

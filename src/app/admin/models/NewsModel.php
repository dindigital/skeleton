<?php

namespace src\app\admin\models;

use src\app\admin\validators\NewsValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\Sequence;
use src\app\admin\helpers\Listbox;
use src\app\admin\helpers\MoveFiles;

/**
 *
 * @package app.models
 */
class NewsModel extends BaseModelAdm
{

  private $_listbox;

  public function __construct ()
  {
    parent::__construct();
    $this->_listbox = new Listbox($this->_dao);
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
    $validator = new validator();
    $id = $validator->setId($this);
    $validator->setIdNewsCat($info['id_news_cat']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setHead($info['head']);
    $validator->setBody($info['body']);
    Sequence::setSequence($this, $validator);
    $validator->setIncDate();
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $id, $mf);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());

    $this->_listbox->insertRelationship('r_news_photo', 'id_news', $id, 'id_photo', $info['r_news_photo']);
    $this->_listbox->insertRelationship('r_news_video', 'id_news', $id, 'id_video', $info['r_news_video']);

    return $id;
  }

  public function update ( $id, $info )
  {
    $validator = new validator();
    $validator->setIdNewsCat($info['id_news_cat']);
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDate($info['date']);
    $validator->setHead($info['head']);
    $validator->setBody($info['body']);
    $mf = new MoveFiles;
    $validator->setFile('cover', $info['cover'], $id, $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById($id);
    $this->_dao->update($validator->getTable(), array('id_news = ?' => $id));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);

    $this->_listbox->insertRelationship('r_news_photo', 'id_news', $id, 'id_photo', $info['r_news_photo']);
    $this->_listbox->insertRelationship('r_news_video', 'id_news', $id, 'id_video', $info['r_news_video']);

    return $id;
  }

  public function getNew ()
  {
    return array(
        'id_news' => null,
        'id_news_cat' => null,
        'active' => null,
        'title' => null,
        'date' => null,
        'head' => null,
        'body' => null,
        'inc_date' => null,
        'del_date' => null,
        'is_del' => null,
        'cover' => null,
        'sequence' => null,
    );
  }

  public function arrayRelationshipPhoto ()
  {
    return $this->_listbox->totalArray('photo', 'id_photo', 'title');
  }

  public function selectedRelationshipPhoto ()
  {
    return $this->_listbox->selectedArray('photo', 'id_photo', 'title', 'r_news_photo', 'id_news', $this->_id);
  }

  public function arrayRelationshipVideo ()
  {
    return $this->_listbox->selectedArray('video', 'id_video', 'title', 'r_news_video', 'id_news', $this->_id);
  }

  public function ajaxRelationshipVideo ( $term )
  {
    return $this->_listbox->ajaxJson('video', 'id_video', 'title', $term);
  }

}

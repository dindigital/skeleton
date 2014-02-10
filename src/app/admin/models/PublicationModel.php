<?php

namespace src\app\admin\models;

use src\app\admin\validators\PublicationValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;
use src\app\admin\helpers\MoveFiles;

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

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'is_del = ?' => '0',
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    $select = new Select('publication');
    $select->addField('id_publication');
    $select->addField('active');
    $select->addField('title');
    $select->where($arrCriteria);
    $select->order_by('title');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $this->setNewId();
    $this->setTimestamp('inc_date');
    $this->setIntval('active', $info['active']);
    $this->setDefaultUri($info['title'], 'publicacoes');

    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);
    $mf = new MoveFiles;
    $validator->setFile('file', $info['file'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $this->_dao->insert($this->_table);
    $this->log('C', $info['title'], $this->_table);
  }

  public function update ( $info )
  {
    $this->setIntval('active', $info['active']);
    $this->setDefaultUri($info['title'], 'publicacoes', $info['uri']);

    $validator = new validator($this->_table);
    $validator->setTitle($info['title']);
    $mf = new MoveFiles;
    $validator->setFile('file', $info['file'], $this->getId(), $mf);
    $validator->throwException();

    $mf->move();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_publication = ?' => $this->getId()));
    $this->log('U', $info['title'], $this->_table, $tableHistory);
  }

}

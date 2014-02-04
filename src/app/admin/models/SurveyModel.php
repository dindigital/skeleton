<?php

namespace src\app\admin\models;

use src\app\admin\validators\SurveyValidator as validator;
use src\app\admin\models\essential\BaseModelAdm;
use Din\DataAccessLayer\Select;
use src\app\admin\helpers\PaginatorAdmin;

/**
 *
 * @package app.models
 */
class SurveyModel extends BaseModelAdm
{

  public function getList ( $arrFilters = array() )
  {
    $arrCriteria = array(
        'title LIKE ?' => '%' . $arrFilters['title'] . '%'
    );

    $select = new Select('survey');
    $select->addField('id_survey');
    $select->addField('active');
    $select->addField('title');
    $select->addField('inc_date');
    $select->where($arrCriteria);
    $select->order_by('inc_date DESC');

    $this->_paginator = new PaginatorAdmin($this->_itens_per_page, $arrFilters['pag']);
    $this->setPaginationSelect($select);

    $result = $this->_dao->select($select);

    return $result;
  }

  public function insert ( $info )
  {
    $validator = new validator();
    $this->setId($validator->setId($this));
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'survey');
    $validator->setIncDate();
    $validator->throwException();

    $this->_dao->insert($validator->getTable());
    $this->log('C', $info['title'], $validator->getTable());
  }

  public function update ( $info )
  {
    $validator = new validator();
    $validator->setActive($info['active']);
    $validator->setTitle($info['title']);
    $validator->setDefaultUri($info['title'], $this->getId(), 'survey', $info['uri']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_survey = ?' => $this->getId()));
    $this->log('U', $info['title'], $validator->getTable(), $tableHistory);
  }

  public function getListArray ()
  {
    $select = new Select('survey');
    $select->addField('id_survey');
    $select->addField('title');
    $select->where(array(
        'is_del = ? ' => '0'
    ));

    $result = $this->_dao->select($select);

    $arrOptions = array();
    foreach ( $result as $row ) {
      $arrOptions[$row['id_survey']] = $row['title'];
    }

    return $arrOptions;
  }

}

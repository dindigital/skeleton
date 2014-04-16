<?php

namespace Site\Models;

use Din\DataAccessLayer\Select;
use Din\Filters\Date\DateFormat;
use Din\Image\Picuri;
use Site\Helpers\PaginatorSite;
use Exception;

class NewsModel extends BaseModelSite
{

  public function getListHome ()
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1'
    );

    $select = new Select('news');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,date DESC');
    $select->limit(3);

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result;

  }

  public function getList ( $pag = 1 )
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1'
    );

    $select = new Select('news');
    $select->addField('title');
    $select->addField('date');
    $select->addField('uri');
    $select->addField('cover');
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $this->_paginator = new PaginatorSite(1, $pag);
    $this->setPaginationSelect($select, 1);

    $result = $this->_dao->select($select);

    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
      if ( !is_null($row['cover']) ) {
        $result[$i]['cover'] = Picuri::picUri($row['cover'], 100, 100, true);
      }
    }

    return $result;

  }

  public function getView ( $uri )
  {

    $uri = "/noticias/{$uri}/";

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1',
        'a.uri = ?' => $uri
    );

    $select = new Select('news');
    $select->addField('title');
    $select->addField('date');
    $select->addField('head');
    $select->addField('body');
    $select->where($arrCriteria);

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $result = $this->_dao->select($select);

    if ( !count($result) ) {
      throw new Exception('Notícia não encontrada');
    }

    foreach ( $result as $i => $row ) {
      $result[$i]['date'] = DateFormat::filter_date($row['date']);
    }

    return $result[0];

  }

}

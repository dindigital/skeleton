<?php

namespace Site\Models\DataAccess;

use Site\Models\DataAccess\AbstractDataAccess;
use Din\DataAccessLayer\Select;
use Site\Models\Entities;
use Din\Paginator\Paginator;

class News extends AbstractDataAccess
{

  public function getList ( Paginator $paginator = null )
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

    if ( $paginator ) {
      $total = $this->_dao->select_count($select);
      $limit = $paginator->getItensPerPage();
      $offset = $paginator->getOffset($total);
      $select->setLimit($limit, $offset);
    }

    $result = $this->_dao->select($select, new Entities\News);

    return $result;

  }

  public function getLast ()
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1'
    );

    $select = new Select('news');
    $select->addAllFields();
    $select->where($arrCriteria);
    $select->order_by('date DESC');
    $select->limit(5);

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $result = $this->_dao->select($select, new Entities\News);

    return $result;

  }

  public function getNews ( $uri )
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1',
        'a.uri = ?' => $uri
    );

    $select = new Select('news');
    $select->addAllFields();
    $select->where($arrCriteria);
    $select->order_by('date DESC');

    $select->inner_join('id_news_cat', Select::construct('news_cat')
                    ->addField('title', 'category'));

    $result = $this->_dao->select($select, new Entities\News);

    return $result;

  }

  public function getNewsSitemap ()
  {

    $criteria = array(
        'is_active = ?' => '1',
        'is_del = ?' => '0'
    );

    $select = new Select('news');
    $select->addField('uri');
    $select->addSField('url', '');
    $select->addField('date');

    $select->where($criteria);

    $result = $this->_dao->select($select, new Entities\News);

    return $result;

  }

}

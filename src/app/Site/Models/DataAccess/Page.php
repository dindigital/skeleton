<?php

namespace Site\Models\DataAccess;

use Site\Models\DataAccess\AbstractDataAccess;
use Din\DataAccessLayer\Select;
use Site\Models\Entities;

class Page extends AbstractDataAccess
{

  public function getNav ( $id_page_cat )
  {
    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1',
        'a.id_page_cat = ?' => $id_page_cat
    );

    $select = new Select('page');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('url');
    $select->addField('target');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,title');

    $result = $this->_dao->select($select, new Entities\Page);

    return $result;

  }

  public function getByFilter ( $filter = array() )
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.is_active = ?' => '1',
    );

    if ( isset($filter['uri']) ) {
      $arrCriteria['a.uri = ?'] = $filter['uri'];
    }

    $select = new Select('page');
    $select->addField('title');
    $select->addField('content');
    $select->addField('keywords');
    $select->addField('description');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select, new Entities\Page);

    return $result;

  }

}

<?php

namespace src\app\site\models;

use Din\DataAccessLayer\Select;
use Exception;

class PageModel extends BaseModelSite
{

  public function getDropdown ( $id_page_cat )
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.active = ?' => '1',
        'a.id_page_cat = ?' => $id_page_cat
    );

    $select = new Select('page');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('url');
    $select->addField('target');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,title');

    $result = $this->_dao->select($select);

    foreach ( $result as $index => $row ) {
      $result[$index]['link'] = $row['url'] ? $row['url'] : $row['uri'];
    }

    return $result;

  }

  public function getView ( $cat, $uri )
  {

    $uri = "/$cat/$uri/";

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.active = ?' => '1',
        'a.uri = ?' => $uri
    );

    $select = new Select('page');
    $select->addField('title');
    $select->addField('content');
    $select->where($arrCriteria);

    $result = $this->_dao->select($select);

    if ( !count($result) ) {
      throw new Exception('Página não encontrada');
    }

    return $result[0];

  }

}

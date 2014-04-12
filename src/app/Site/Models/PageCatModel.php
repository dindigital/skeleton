<?php

namespace Site\Models;

use Din\DataAccessLayer\Select;
use Din\Http\Header;
use Exception;

class PageCatModel extends BaseModelSite
{

  public function getNav ()
  {

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.active = ?' => '1'
    );

    $select = new Select('page_cat');
    $select->addField('id_page_cat');
    $select->addField('title');
    $select->addField('uri');
    $select->addField('url');
    $select->addField('target');
    $select->where($arrCriteria);
    $select->order_by('a.sequence=0,a.sequence,title');
    $select->limit(6);

    $result = $this->_dao->select($select);

    $pageModel = new PageModel();

    foreach ( $result as $index => $row ) {

      $result[$index]['link'] = $row['url'] ? $row['url'] : $row['uri'];
      $uri = explode('/', Header::getUri());
      $uri = $uri[1] == '' ? '/' : "/$uri[1]/";
      $result[$index]['class'] = $result[$index]['link'] == $uri ? 'active' : '';
      $result[$index]['dropdown'] = $pageModel->getDropdown($row['id_page_cat']);
      if ( count($result[$index]['dropdown']) ) {
        $result[$index]['class'] .= ' dropdown';
      }
    }

    return $result;

  }

  public function getView ( $uri )
  {

    $uri = "/$uri/";

    $arrCriteria = array(
        'a.is_del = ?' => '0',
        'a.active = ?' => '1',
        'a.uri = ?' => $uri
    );

    $select = new Select('page_cat');
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

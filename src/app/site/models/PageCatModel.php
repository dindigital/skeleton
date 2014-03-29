<?php

namespace src\app\site\models;

use Din\DataAccessLayer\Select;
use Din\Http\Header;

class PageCatModel extends BaseModelSite
{
  
    public function getNav() {
        
        $arrCriteria = array(
            'a.is_del = ?' => '0',
            'a.active = ?' => '1'
        );
        
        $select = new Select('page_cat');
        $select->addField('id_page_cat');
        $select->addField('title');
        $select->addField('uri');
        $select->where($arrCriteria);
        $select->order_by('a.sequence=0,a.sequence,title');
        $select->limit(6);

        $result = $this->_dao->select($select);
        
        foreach ($result as $index => $row) {
           $uri = explode('/', Header::getUri());
           $uri = $uri[1] == '' ? '/' : "/$uri[1]/";
           $result[$index]['class'] = $row['uri'] == $uri ? 'active' : '';
           $result[$index]['dropdown'] = $this->getDropdown($row['id_page_cat']);
           if (count($result[$index]['dropdown'])) {
                $result[$index]['class'] .= ' dropdown';
           }
        }

        return $result;
        
    }
    
    protected function getDropdown($id_page_cat) {
        
        $arrCriteria = array(
            'a.is_del = ?' => '0',
            'a.active = ?' => '1',
            'a.id_page_cat = ?' => $id_page_cat
        );
        
        $select = new Select('page');
        $select->addField('title');
        $select->addField('uri');
        $select->where($arrCriteria);
        $select->order_by('a.sequence=0,a.sequence,title');

        $result = $this->_dao->select($select);

        return $result;
        
    }
    
}
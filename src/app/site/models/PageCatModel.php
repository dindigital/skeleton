<?php

namespace src\app\site\models;

use Din\DataAccessLayer\Select;

class PageCatModel extends BaseModelSite
{
  
    public function getNav() {
        
        $arrCriteria = array(
            'a.is_del = ?' => '0',
            'a.active = ?' => '1'
        );
        
        $select = new Select('page_cat');
        $select->addField('title');
        $select->addField('uri');
        $select->where($arrCriteria);
        $select->order_by('a.sequence=0,a.sequence,title');
        $select->limit(6);

        $result = $this->_dao->select($select);

        return $result;
        
    }
    
}
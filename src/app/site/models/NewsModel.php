<?php

namespace src\app\site\models;

use Din\DataAccessLayer\Select;
use Din\Filters\Date\DateFormat;

class NewsModel extends BaseModelSite
{
  
    public function getList() {
        
        $arrCriteria = array(
            'a.is_del = ?' => '0',
            'a.active = ?' => '1'
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
    
    public function getView($uri) {
        
        $uri = "/noticias/{$uri}/";
        
        $arrCriteria = array(
            'a.is_del = ?' => '0',
            'a.active = ?' => '1',
            'a.uri = ?' => $uri
        );
        
        $select = new Select('news');
        $select->addField('title');
        $select->addField('date');
        $select->addField('uri');
        $select->where($arrCriteria);

        $select->inner_join('id_news_cat', Select::construct('news_cat')
                        ->addField('title', 'category'));

        $result = $this->_dao->select($select);
        
        if (!count($result)) {
            throw new Exception('Notícia não encontrada');
        }

        foreach ( $result as $i => $row ) {
          $result[$i]['date'] = DateFormat::filter_date($row['date']);
        }

        return $result[0];
        
    }
    
}
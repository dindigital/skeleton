<?php

namespace Site\Models\DataAccess\Find\News;

use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Find\AbstractFind;

class News extends AbstractFind
{

    public function __construct ()
    {
        parent::__construct();

        $this->_select = new Select2('news');
        $this->_select->addField('id_news');
        $this->_select->addField('title');
        $this->_select->addField('body');
        $this->_select->addField('inc_date');
        $this->_select->addField('head');
        $this->_select->addField('cover');
        $this->_select->addField('uri');
        $this->_select->addField('description');
        $this->_select->addField('keywords');

        $this->_select->limit(1);

        $this->_criteria = array(
            'is_del = ?' => 0,
            'is_active = ?' => 1
        );

    }

    public function setUri ( $uri )
    {
        $this->_criteria['uri = ?'] = $uri;

    }

    public function prepare ()
    {
        $this->_select->where(new Criteria($this->_criteria));

    }

    public function getEntity ()
    {
        $result = $this->_dao->select($this->_select, new \Site\Models\DataAccess\Entity\News);

        if ( !count($result) )
            throw new \Site\Models\DataAccess\Find\Exception\ContentNotFoundException('Conteúdo não encontrado.');

        return $result[0];

    }

}

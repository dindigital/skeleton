<?php

namespace Site\Models\DataAccess\Find\Page;

use Site\Models\DataAccess\Connection\AbstractDAOClient;
use Site\Models\DataAccess\Entity;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Collection\PageCollection;

class Page extends AbstractDAOClient
{

  protected $_strategy;

  public function __construct ( StrategyInterface $strategy )
  {
    parent::__construct();
    $this->_strategy = $strategy;

  }

  public function getByUri ( $uri )
  {
    $select = new Select2($this->_strategy->getTableName());
    $select->addField($this->_strategy->getIdName(), 'id_page');
    $select->addField('title');
    $select->addField('content');
    $select->addField('description');
    $select->addField('keywords');
    $select->addField('short_link');
    $select->addField('uri');
    $select->addSField($this->_strategy->getTableName(), 'area');

    $select->where(new Criteria(array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'uri = ?' => $uri
    )));

    $select->limit(1);

    $result = $this->_dao->select_iterator($select, new Entity\Page, new PageCollection);

    return $result;

  }

}

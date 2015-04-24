<?php

namespace Site\Models\DataAccess\Find\News\Areas;

use Site\Models\DataAccess\Find\News\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;
use Site\Models\DataAccess\Find\News\NewsType;

class Common extends AreaInterface
{

  public function getSelect ()
  {
    $select = new Select2('news');
    $select->addField('id_news');
    $select->addField('uri');
    $select->addField('title');
    $select->addField('date');
    $select->addField('cover');
    $select->addField('head');

    $arrCriteria = array(
        'is_active = ?' => 1,
        'is_del = ?' => 0,
        'type IN (?)' => array(
            NewsType::COMMON,
        )
    );

    if ( count($this->_skip_ids) ) {
      $arrCriteria['id_news NOT IN (?)'] = $this->_skip_ids;
    }

    if ( $this->_term ) {
      $arrCriteria['OR'] = array(
          'title LIKE ?' => $this->_term_like,
          'description LIKE ?' => $this->_term_like,
          'keywords LIKE ?' => $this->_term_like,
      );
    }

    $select->where(new Criteria($arrCriteria));

    return $select;

  }

}

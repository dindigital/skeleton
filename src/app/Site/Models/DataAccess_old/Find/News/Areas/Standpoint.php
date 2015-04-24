<?php

namespace Site\Models\DataAccess\Find\News\Areas;

use Site\Models\DataAccess\Find\News\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Standpoint extends AreaInterface
{

  private function inner_join_author ( Select2 $select )
  {
    $select->inner_join('r_standpoint_standpoint_author', 'id_standpoint', 'id_standpoint');
    $select->inner_join('standpoint_author', 'id_standpoint_author', 'id_standpoint_author', 'r_standpoint_standpoint_author');

  }

  public function getSelect ()
  {
    $select = new Select2('standpoint');
    $select->addField('id_standpoint');
    $select->addField('uri');
    $select->addField('title');
    $select->addField('date');

    $this->inner_join_author($select);
    $select->addField('cover', 'cover', 'standpoint_author');

    $select->addField('description', 'head');


    $arrCriteria = array(
        'standpoint.is_active = ?' => 1,
        'standpoint.is_del = ?' => 0,
        'standpoint_author.is_active = ?' => 1,
        'standpoint_author.is_del = ?' => 0,
    );

    if ( $this->_term ) {
      $arrCriteria['OR'] = array(
          'standpoint.title LIKE ?' => $this->_term_like,
          'standpoint.description LIKE ?' => $this->_term_like,
          'standpoint.keywords LIKE ?' => $this->_term_like,
      );
    }

    $select->where(new Criteria($arrCriteria));

    $select->group_by('standpoint.id_standpoint');

    return $select;

  }

}

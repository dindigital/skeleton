<?php

namespace Site\Models\DataAccess\Find\RSS\Areas;

use Site\Models\DataAccess\Find\RSS\AreaInterface;
use Din\DataAccessLayer\Select\Select as Select2;
use Din\DataAccessLayer\Criteria\Criteria;

class Standpoint implements AreaInterface
{

  protected $_select;
  protected $_criteria = array();

  public function __construct ()
  {
    $this->_select = new Select2('standpoint');
    $this->_select->addSField('Artigos', 'area');
    $this->_select->addField('id_standpoint', 'id');
    $this->_select->addField('title');
    $this->_select->addField('description', 'head');
    $this->_select->addField('date');
    $this->_select->addField('cover', 'cover', 'standpoint_author');
    $this->_select->addField('uri');

    $this->_select->inner_join('r_standpoint_standpoint_author', 'id_standpoint', 'id_standpoint');
    $this->_select->inner_join('standpoint_author', 'id_standpoint_author', 'id_standpoint_author', 'r_standpoint_standpoint_author');

    $this->_criteria['standpoint.is_active = ?'] = 1;
    $this->_criteria['standpoint.is_del = ?'] = 0;
    $this->_criteria['standpoint_author.is_del = ?'] = 0;
    $this->_criteria['standpoint_author.is_del = ?'] = 0;

  }

  public function getSelect ()
  {
    $this->_select->where(new Criteria($this->_criteria));

    return $this->_select;

  }

}

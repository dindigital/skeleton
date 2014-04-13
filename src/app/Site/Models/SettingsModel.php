<?php

namespace Site\Models;

use Din\DataAccessLayer\Select;
use Exception;

class SettingsModel extends BaseModelSite
{

  public function getSettings ()
  {

    $select = new Select('settings');
    $select->addField('home_title');
    $select->addField('home_description');
    $select->addField('home_keywords');
    $select->addField('title');
    $select->addField('description');
    $select->addField('keywords');
    $select->limit(1);

    $result = $this->_dao->select($select);

    if ( !count($result) ) {
      throw new Exception('Tabela de configurações está vazia.');
    }

    return $result[0];

  }

}

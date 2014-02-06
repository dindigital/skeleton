<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\validators\SettingsValidator as validator;

/**
 *
 * @package app.models
 */
class SettingsModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setTable('settings');
  }

  public function update ( $info )
  {
    $validator = new validator($this->_table);
    $validator->setHomeTitle($info['home_title']);
    $validator->setHomeDescription($info['home_description']);
    $validator->setHomeKeywords($info['home_keywords']);
    $validator->setTitle($info['title']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($this->_table, array('id_settings = ?' => $this->getId()));
    $this->log('U', 'Configurações', $this->_table, $tableHistory);
  }

}

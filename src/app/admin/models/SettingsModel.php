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

  public function update ( $info )
  {
    $validator = new validator();
    $validator->setHomeTitle($info['home_title']);
    $validator->setHomeDescription($info['home_description']);
    $validator->setHomeKeywords($info['home_keywords']);
    $validator->setTitle($info['title']);
    $validator->setDescription($info['description']);
    $validator->setKeywords($info['keywords']);
    $validator->throwException();

    $tableHistory = $this->getById();
    $this->_dao->update($validator->getTable(), array('id_settings = ?' => $this->getId()));
    $this->log('U', 'Configurações', $validator->getTable(), $tableHistory);
  }

}

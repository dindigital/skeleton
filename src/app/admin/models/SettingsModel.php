<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\validators\BaseValidator as validator;

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
    $validator->setInput($info);
    $validator->setRequiredString('home_title', 'Título Home');
    $validator->setRequiredString('home_description', 'Description Home');
    $validator->setRequiredString('home_keywords', 'Keywords Home');
    $validator->setRequiredString('title', 'Título Internas');
    $validator->setRequiredString('description', 'Description Internas');
    $validator->setRequiredString('keywords', 'Keywords Internas');
    $validator->throwException();

    $this->dao_update();
  }

}

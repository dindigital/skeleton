<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use src\app\admin\validators\BaseValidator as validator;
use Din\Filters\String\Html;

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

  protected function formatTable ( $table )
  {
    $table['home_title'] = Html::scape($table['home_title']);
    $table['home_description'] = Html::scape($table['home_description']);
    $table['home_keywords'] = Html::scape($table['home_keywords']);
    $table['title'] = Html::scape($table['title']);
    $table['description'] = Html::scape($table['description']);
    $table['keywords'] = Html::scape($table['keywords']);

    return $table;
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

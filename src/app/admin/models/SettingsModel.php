<?php

namespace src\app\admin\models;

use src\app\admin\models\essential\BaseModelAdm;
use Din\Filters\String\Html;
use src\app\admin\validators\StringValidator;
use src\app\admin\filters\TableFilter;
use Din\Exception\JsonException;

/**
 *
 * @package app.models
 */
class SettingsModel extends BaseModelAdm
{

  public function __construct ()
  {
    parent::__construct();
    $this->setEntity('settings');
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

  public function update ( $input )
  {
    $str_validator = new StringValidator($input);
    $str_validator->validateRequiredString('home_title', 'Título Home');
    $str_validator->validateRequiredString('home_description', 'Description Home');
    $str_validator->validateRequiredString('home_keywords', 'Keywords Home');
    $str_validator->validateRequiredString('title', 'Título Internas');
    $str_validator->validateRequiredString('description', 'Description Internas');
    $str_validator->validateRequiredString('keywords', 'Keywords Internas');

    JsonException::throwException();

    $filter = new TableFilter($this->_table, $input);
    $filter->setString('home_title');
    $filter->setString('home_description');
    $filter->setString('home_keywords');
    $filter->setString('title');
    $filter->setString('description');
    $filter->setString('keywords');

    $this->dao_update();
  }

}

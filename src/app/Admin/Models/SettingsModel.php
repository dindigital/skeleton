<?php

namespace Admin\Models;

use Admin\Models\Essential\BaseModelAdm;
use Din\Filters\String\Html;
use Admin\CustomFilter\TableFilterAdm as TableFilter;
use Din\InputValidator\InputValidator;

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

  protected function formatTable ( $table, $exclude_fields = false )
  {
    if ( $exclude_fields ) {
      //
    }

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
    $v = new InputValidator($input);
    $v->string()->validate('home_title', 'Título Home');
    $v->string()->validate('home_description', 'Description Home');
    $v->string()->validate('home_keywords', 'Keywords Home');
    $v->string()->validate('title', 'Título Internas');
    $v->string()->validate('description', 'Description Internas');
    $v->string()->validate('keywords', 'Keywords Internas');
    $v->throwException();
    //
    $f = new TableFilter($this->_table, $input);
    $f->string()->filter('home_title');
    $f->string()->filter('home_description');
    $f->string()->filter('home_keywords');
    $f->string()->filter('title');
    $f->string()->filter('description');
    $f->string()->filter('keywords');

    $this->dao_update();

  }

}

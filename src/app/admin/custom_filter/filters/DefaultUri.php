<?php

namespace src\app\admin\custom_filter\filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\LimitChars;
use Din\Filters\String\Uri;

class DefaultUri extends AbstractFilter
{

  protected $_title;
  protected $_id;
  protected $_prefix;

  public function __construct ( $title, $id = '', $prefix = '' )
  {
    $this->_title = $title;
    $this->_id = $id;
    $this->_prefix = $prefix;
  }

  public function filter ( $field )
  {
    $title = $this->getValue($this->_title);
    $uri = $this->getValue($field);

    $this->_id = substr($this->_id, 0, 4);

    $uri = $uri == '' ? Uri::format($title) : Uri::format($uri);
    $uri = LimitChars::filter($uri, 80, '');
    if ( $this->_prefix != '' ) {
      $this->_prefix = '/' . Uri::format($this->_prefix);
    }

    if ( $this->_id != '' ) {
      $this->_table->uri = "{$this->_prefix}/{$uri}-{$this->_id}/";
    } else {
      $this->_table->uri = "{$this->_prefix}/{$uri}/";
    }
  }

}

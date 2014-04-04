<?php

namespace src\app\admin\custom_filter\filters;

use Din\TableFilter\AbstractFilter;
use Din\Filters\String\LimitChars;
use Din\Filters\String\Uri;

class DefaultUri extends AbstractFilter
{

  protected $_id;
  protected $_prefix;

  public function setOptions ( $id = '', $prefix = '' )
  {
    $this->_id = $id;
    $this->_prefix = $prefix;
  }

  public function filter ( $title_field )
  {
    $title = $this->getValue($title_field);
    $uri = $this->getValue('uri');
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

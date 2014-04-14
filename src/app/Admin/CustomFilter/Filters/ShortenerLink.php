<?php

namespace Admin\CustomFilter\Filters;

use Din\TableFilter\AbstractFilter;

class ShortenerLink extends AbstractFilter
{

  public function filter ( $field )
  {
    if ( URL && BITLY && $this->_table->uri ) {
      $url = URL . $this->_table->uri;
      $bitly = new Bitly(BITLY);
      $bitly->shorten($url);
      if ( $bitly->check() ) {
        $this->_table->short_link = $bitly;
      }
    }

  }

}

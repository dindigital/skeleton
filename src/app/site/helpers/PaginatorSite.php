<?php

namespace src\app\site\helpers;

use Din\Paginator\Paginator;

/**
 *
 * @package Paginator
 */
class PaginatorSite extends Paginator
{

  public function __construct ( $itens_per_page, $current_pag = 0 )
  {
    parent::__construct($itens_per_page, 7, $current_pag);

    $this->_active_class = 'active';
    $this->_disabled_class = 'disabled';

    $this->_first = null;
    $this->_prev = '<li class="{$disabled}"><a href="{$link}">&laquo;</a></li>';
    $this->_numbers = '<li class="{$active}"><a href="{$link}">{$n}</a></li>';
    $this->_next = '<li class="next {$disabled}"><a href="{$link}">&raquo;</a></li>';
    $this->_last = null;

    $this->_order = '{$_prev}{$_numbers}{$_next}';

  }

}

// END
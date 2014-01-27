<?php

namespace src\app\admin\helpers;

use Din\Paginator\Paginator;

/**
 *
 * @package Paginator
 */
class PaginatorAdmin extends Paginator
{

  public function __construct ( $itens_per_page, $current_pag = 0 )
  {
    parent::__construct($itens_per_page, 7, $current_pag);

    $this->_active_class = 'active';
    $this->_disabled_class = 'disabled';

    $this->_first = '<li class="prev {$disabled}"><a href="{$link}"><span class="fa fa-angle-left"></span><span class="fa fa-angle-left"></span>&nbsp;Primeira</a></li>';
    $this->_prev = '<li class="prev {$disabled}"><a href="{$link}"><span class="fa fa-angle-left"></span>&nbsp;Anterior</a></li>';
    $this->_numbers = '<li class="{$active}"><a href="{$link}">{$n}</a></li>';
    $this->_next = '<li class="next {$disabled}"><a href="{$link}">Próxima&nbsp;<span class="fa fa-angle-right"></span></a></li>';
    $this->_last = '<li class="next {$disabled}"><a href="{$link}">Última&nbsp;<span class="fa fa-angle-right"></span><span class="fa fa-angle-right"></span></a></li>';

    $this->_order = '{$_first}{$_prev}{$_numbers}{$_next}{$_last}';
  }

}

// END
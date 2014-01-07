<?php

namespace src\app\admin\helpers;

use Din\Paginator\Paginator;

/**
 *
 * @package Paginator
 */
class PaginatorPainel extends Paginator
{

  /**
   *
   * @param int $_itens_por_pag
   * @param int $_qtd_numeros
   * @param int $_atual_pag
   */
  public function __construct ( $_itens_por_pag, $_qtd_numeros, $_atual_pag = 0 )
  {
    parent::__construct($_itens_por_pag, $_qtd_numeros, $_atual_pag);

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
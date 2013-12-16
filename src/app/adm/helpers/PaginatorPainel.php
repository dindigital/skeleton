<?php

namespace src\app\adm\helpers;

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

    $this->_first = '<a href="{$link}" class="replace_container {$disabled}">Primeira</a>';
    $this->_prev = '<a href="{$link}" class="replace_container {$disabled}">Anterior</a>';
    $this->_numbers = '<a href="{$link}" class="replace_container {$active}">{$n}</a>';
    $this->_next = '<a href="{$link}" class="replace_container {$disabled}">Próxima</a>';
    $this->_last = '<a href="{$link}" class="replace_container {$disabled}">Última</a>';

    $this->_order = '{$_first}{$_prev}{$_numbers}{$_next}{$_last}';
  }

}

// END
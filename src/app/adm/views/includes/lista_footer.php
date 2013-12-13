<? if ( !count($this->list) ): ?>
  <div class="noresult">Sua pesquisa não retornou resultados.</div>
<? endif; ?>

<div class="tablePagination">
  <div class="tablePagination_info">Exibindo <?= $this->paginator->getSubTotal() ?> de <?= $this->paginator->getTotal() ?> resultados</div>
  <div class="tablePagination_btns">
    <?= $this->paginator->getNumbers() ?>
  </div>
</div>
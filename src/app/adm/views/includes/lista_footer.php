<?php if ( !count($data['list']) ): ?>
  <div class="noresult">Sua pesquisa não retornou resultados.</div>
<?php endif; ?>

<div class="tablePagination">
  <div class="tablePagination_info">Exibindo <?php echo $data['paginator']['subtotal']; ?> de <?php echo $data['paginator']['total']; ?> resultados</div>
  <div class="tablePagination_btns">
    <?php echo $data['paginator']['numbers']; ?>
  </div>
</div>
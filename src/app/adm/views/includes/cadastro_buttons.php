<button type="button" class="replace_container" href="<?= $this->uri->lista ?>">
  <img height="24" width="24" alt="Voltar para a lista" src="/adm/images/list.png">
  <span>Voltar para a lista</span>
</button>
<? if ( isset($this->table->link) ): ?>
<button type="button" target="_blank" href="http://<?= str_replace('http://', '', $this->table->link) ?>" class="link">
    <img height="24" width="24" alt="Visualizar" src="/adm/images/preview.png">
    <span>Visualizar</span>
  </button>
<? endif; ?>
<? include 'btn_novo_registro.php' ?>
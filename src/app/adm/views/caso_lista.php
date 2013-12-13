<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <input name="nome" type="text" placeholder="Busca por nome" value="<?= $this->busca->nome ?>">

    <? include 'includes/lista_buscar.php' ?>
  </form>
</div>

<div class="button_bar clearfix">
  <? include 'includes/lista_buttons.php' ?>
</div>

<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th class="typeCheck"><input class="uniform" type="checkbox" name="excluir[]" id="excluir_all" /></th>
        <th class="typeStatus">Ativo</th>
        <th class="typeStatus">Ordem</th>
        <th class="txtleft">Nome</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_<?= $table->getName(true) ?>_<?= $table->getPkValue() ?>" /></td>
          <td class="typeStatus"><input class="uniform setAtivo" type="checkbox" <? if ( $table->ativo == '1' ): ?> checked="checked"<? endif ?> id="<?= $table->getPkValue() ?>" /></td>
          <td><?php echo $table->ordem; ?></td>
          <td>
            <?= $table->nome ?>
            <ul>
              <? include 'includes/lista_editar.php' ?>
              <? include 'includes/lista_visualizar.php' ?>
              <? include 'includes/lista_excluir.php' ?>
            </ul>
          </td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


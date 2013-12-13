<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <input name="rz_social" type="text" placeholder="Busca por razão" value="<?= $this->busca->rz_social ?>">
    <input name="nome_contato" type="text" placeholder="Busca por contato" value="<?= $this->busca->nome_contato ?>">

    <? include 'includes/lista_buscar.php' ?>
  </form>
</div>

<div class="button_bar clearfix">
  <? include 'includes/btn_novo_registro.php' ?>
  <? include 'includes/btn_excluir_permanentemente.php' ?>
</div>

<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th class="typeCheck"><input class="uniform" type="checkbox" name="excluir[]" id="excluir_all" /></th>
        <th class="typeStatus">Ativo</th>
        <th class="txtleft">Razão Social</th>
        <th class="txtleft">Nome Contato</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_<?= $table->getName(true) ?>_<?= $table->getPkValue() ?>" /></td>
          <td class="typeStatus"><input class="uniform setAtivo" type="checkbox" <? if ( $table->ativo == '1' ): ?> checked="checked"<? endif ?> id="<?= $table->getPkValue() ?>" /></td>
          <td>
            <?= $table->rz_social ?>
            <ul>
              <? include 'includes/lista_editar.php' ?>
              <? include 'includes/lista_visualizar.php' ?>
              <? include 'includes/lista_excluir_direto.php' ?>
            </ul>
          </td>
          <td><?= $table->nome_contato ?></td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


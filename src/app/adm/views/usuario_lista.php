<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <input name="nome" type="text" placeholder="Busca por nome" value="<?= $this->busca->nome ?>">
    <input name="email" type="text" placeholder="Busca por email" value="<?= $this->busca->email ?>">

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
        <th class="txtleft">Nome</th>
        <th class="txtleft" style="width:300px">E-mail</th>
        <th class="txtleft" style="width:150px">Data de Inclusão</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr<? if ( isset($table->del_pai) && $table->del_pai == '1' ): ?> class="del_pai"<? endif ?>>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_<?= $table->getName(true) ?>_<?= $table->getPkValue() ?>" /></td>
          <td class="typeStatus"><input class="uniform setAtivo" type="checkbox" <? if ( $table->ativo == '1' ): ?> checked="checked"<? endif ?> id="<?= $table->getPkValue() ?>" /></td>
          <td>
            <?= $table->nome ?>
            <ul>
              <? include 'includes/lista_editar.php' ?>
              <? include 'includes/lista_excluir_direto.php' ?>
            </ul>
          </td>
          <td><?= $table->email ?></td>
          <td><?php echo \lib\Validation\DateTransform::format_date($table->inc_data, 'full') ?></td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


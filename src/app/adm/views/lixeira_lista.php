<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <input name="titulo" type="text" placeholder="Busca por título" value="<?= $this->busca->titulo ?>" />
    <?= $this->busca->secao ?>

    <? include 'includes/lista_buscar.php' ?>
  </form>
</div>

<div class="button_bar clearfix">
  <? include 'includes/btn_restaurar.php' ?>
  <? include 'includes/btn_excluir_permanentemente.php' ?>
</div>


<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th class="typeCheck"><input class="uniform" type="checkbox" name="excluir[]" id="excluir_all" /></th>
        <th class="txtleft" style="width:160px">Data</th>
        <th class="txtleft">Título</th>
        <th class="txtleft" style="width:180px">Seção</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_<?= $table->tbl ?>_<?= $table->id ?>" /></td>
          <td class="txtleft"><?= \lib\Validation\DateTransform::format_date($table->del_data, 'full') ?></td>
          <td><?= $table->titulo ?></td>
          <td class="txtleft"><?= $table->secao ?></td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


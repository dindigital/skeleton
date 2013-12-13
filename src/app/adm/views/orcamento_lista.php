<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <?= $this->busca->situacao ?>
    <input name="cliente" type="text" placeholder="Busca por cliente" value="<?= $this->busca->cliente ?>">
    <input name="data1" type="text" placeholder="de" value="<?= $this->busca->data1 ?>" class="datepicker dataMask">
    <input name="data2" type="text" placeholder="ate" value="<?= $this->busca->data2 ?>" class="datepicker dataMask">

    <? include 'includes/lista_buscar.php' ?>
  </form>
</div>

<div class="button_bar clearfix">
  <? include 'includes/btn_excluir_permanentemente.php' ?>
</div>

<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th class="typeCheck"><input class="uniform" type="checkbox" name="excluir[]" id="excluir_all" /></th>
        <th class="txtleft">Situacao</th>
        <th class="txtleft">Tipo</th>
        <th class="txtleft">Cliente</th>
        <th class="txtleft">Data</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_<?= $table->getName(true) ?>_<?= $table->getPkValue() ?>" /></td>
          <td width="150">
            <?= \src\app\adm005\objects\Arrays::arraySituacaoPedido($table->situacao) ?>
            <ul>
              <li><a href="<?= $this->uri->cadastro ?><?= $table->getPkValue() ?>/" title="Detalhes" class="replace_container">Detalhes</a></li>
                <? include 'includes/lista_excluir_direto.php' ?>
            </ul>
          </td>
          <td><?= \src\app\adm005\objects\Arrays::arrayTipoPedido($table->tipo) ?></td>
          <td><?= $table->cliente ?></td>
          <td><?= lib\Validation\DateTransform::format_date($table->inc_data, 'full') ?></td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


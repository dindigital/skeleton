<div id="top_container">
  <h2>Lista de <?= $this->_model->getMe() ?></h2>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div id="search_table">
  <form id="main_form" method="get" class="form_busca" action="<?= $this->action ?>">
    <? include 'includes/hidden_fields.php' ?>

    <?= $this->busca->responsavel ?>
    <?= $this->busca->crud ?>
    <?= $this->busca->secao ?>

    <? include 'includes/lista_buscar.php' ?>
  </form>
</div>

<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th style="width:170px">Data</th>
        <th style="width:200px" class="">Responsável</th>
        <th style="width:220px" class="">Ação</th>
        <th style="width:170px" class="txtleft">Secao</th>
        <th class="txtleft">Descrição</th>
      </tr>
    </thead>

    <tbody>
      <? foreach ( $this->list as $table ): ?>
        <tr href="/adm005/log/cadastro/<?= $table->id_log ?>/" class="replace_container">
          <th><?= lib\Validation\DateTransform::format_date($table->data, 'full') ?></th>
          <th><?= $table->responsavel ?></th>
          <th>
            <?= src\app\adm005\objects\Arrays::arrayLogCrud($table->crud) ?>
          </th>
          <td><?= $table->nome_legivel ?></td>
          <td><?= $table->descricao ?></td>
        </tr>
      <? endforeach ?>
    </tbody>
  </table>
  <? include 'includes/lista_footer.php' ?>
</div>


<div id="top_container">
  <h2>Cadastro de <?= $this->_model->getMe() ?></h2>
  <p>&nbsp;</p>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div class="button_bar clearfix">
  <button type="button" class="replace_container" href="<?= $this->uri->lista ?>">
    <img height="24" width="24" alt="Voltar para a lista" src="/backend/images/list.png">
    <span>Voltar para a lista</span>
  </button>
</div>

<div class="box">

  <form class="validate_form" method="post" id="main_form" action="">
    <fieldset class="label_side">
      <label for="required_field">Pedido</label>
      <div>
        <?= $this->table->id_pedido ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Tipo</label>
      <div>
        <?= \src\app\adm005\objects\Arrays::arrayTipoPedido($this->table->tipo) ?>
      </div>
    </fieldset>

    <?php if ( $this->table->servico ): ?>
      <fieldset class="label_side">
        <label for="required_field">Serviço</label>
        <div>
          <?= $this->table->servico ?>
        </div>
      </fieldset>
    <?php endif; ?>

    <?php if ( $this->table->produto ): ?>
      <fieldset class="label_side">
        <label for="required_field">Produto</label>
        <div>
          <?= $this->table->produto ?>
        </div>
      </fieldset>
    <?php endif; ?>

    <?php if ( $this->table->marca ): ?>
      <fieldset class="label_side">
        <label for="required_field">Marca</label>
        <div>
          <?= $this->table->marca ?>
        </div>
      </fieldset>
    <?php endif; ?>

    <?php if ( $this->table->qtd ): ?>
      <fieldset class="label_side">
        <label for="required_field">Quantidade</label>
        <div>
          <?= $this->table->qtd ?>
        </div>
      </fieldset>
    <?php endif; ?>

    <?php if ( $this->table->n_serie ): ?>
      <fieldset class="label_side">
        <label for="required_field">Número de Série</label>
        <div>
          <?= $this->table->n_serie ?>
        </div>
      </fieldset>
    <?php endif; ?>

    <fieldset class="label_side">
      <label for="required_field">Descrição</label>
      <div>
        <?= $this->table->descricao ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Respostas</label>
      <div>
        <?php foreach ( $this->table->orcamento as $orcamento ): ?>
          <p><?php echo lib\Validation\DateTransform::format_date($orcamento->inc_data, 'full'); ?></p>
          <table class="lista_log" width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <th width="200">
                Fornecedor
              </th>
              <td>
                <?php echo $orcamento->fornecedor; ?>
              </td>
            </tr>
            <tr>
              <th width="200">
                Preço
              </th>
              <td>
                <?php echo \lib\Validation\StringTransform::format_money($orcamento->preco); ?>
              </td>
            </tr>
            <tr>
              <th width="200">
                Prazo de Entrega
              </th>
              <td>
                <?php echo $orcamento->prazo; ?>
              </td>
            </tr>
            <tr>
              <th width="200">
                Forma de Pagamento
              </th>
              <td>
                <?php echo $orcamento->forma_pgto; ?>
              </td>
            </tr>
            <tr>
              <th width="200">
                Descrição
              </th>
              <td>
                <?php echo $orcamento->descricao; ?>
              </td>
            </tr>
          </table>
          <br />
        <?php endforeach; ?>
      </div>
    </fieldset>


  </form>

</div>
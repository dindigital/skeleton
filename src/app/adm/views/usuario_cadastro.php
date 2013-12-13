<div id="top_container">
  <h2>Cadastro de <?= $this->_model->getMe() ?></h2>
  <p>&nbsp;</p>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div class="button_bar clearfix">
  <? include 'includes/cadastro_buttons.php' ?>
</div>

<div class="box">

  <form class="validate_form" method="post" id="main_form" action="<?= $this->action ?>">

    <fieldset class="label_side top">
      <label>Ativo ?</label>
      <div>
        <input class="uniform" type="checkbox" name="ativo" id="ativo" <? if ( $this->table->ativo == '1' ): ?>checked="checked"<? endif; ?> />
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Nome</label>
      <div>
        <input name="nome" type="text" class="required" value="<?= $this->table->nome ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">E-mail</label>
      <div>
        <input name="email" type="text" class="required" value="<?= $this->table->email ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Senha</label>
      <div>
        <input name="senha" type="password" class="<? if ( !$this->table->id_usuario ): ?>required<? endif ?>">
        <? if ( !$this->table->id_usuario ): ?><div class="required_tag"></div><? endif ?>
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Avatar</label>
      <div>
        <?= $this->table->avatar ?>
      </div>
    </fieldset>

    <? include 'includes/cadastro_submit.php' ?>

  </form>

</div>
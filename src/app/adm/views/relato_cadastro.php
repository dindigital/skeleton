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
    <fieldset class="label_side">
      <label>Ativo ?</label>
      <div>
        <input class="uniform" type="checkbox" name="ativo" id="ativo" <? if ( $this->table->ativo == '1' ): ?>checked="checked"<? endif; ?> />
      </div>
    </fieldset>
        
    <fieldset class="label_side">
      <label for="required_field">Nome</label>
      <div>
        <input name="nome" type="text" class="required" value="<?= $this->table->nome ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>
    
    <fieldset class="label_side">
      <label for="required_field">Comentário</label>
      <div class="clearfix">
        <textarea name="comentario" class="autogrow"><?= $this->table->comentario ?></textarea>
      </div>
    </fieldset>
    
    <fieldset class="label_side">
      <label for="required_field">Data</label>
      <div>
        <input name="data" type="text" class="required dataMask datepicker" value="<?= $this->table->data ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>
    
    <? include 'includes/cadastro_submit.php' ?>

  </form>

</div>
<div id="top_container">
  <h2>Cadastro de Foto</h2>
  <p>&nbsp;</p>
</div>

{$CADASTRO_ALERTS}

<div class="button_bar clearfix">
  <button type="button" class="link" href="/adm/usuario/lista/">
    <img height="24" width="24" alt="Voltar para a lista" src="/adm/images/list.png">
    <span>Voltar para a lista</span>
  </button>
  <button type="button" class="link boxradios" href="/adm/usuario/cadastro/">
    <img height="24" width="24" alt="Voltar para a lista" src="/adm/images/create_write.png">
    <span>Novo Registro</span>
  </button>
</div>

<div class="box">

  <form class="validate_form" method="post" id="main_form">

    <fieldset class="label_side top">
      <label>Ativo ?</label>
      <div>
        <input class="uniform" type="checkbox" name="ativo" id="ativo" <?php if ( @$data['table']['ativo'] == '1' ): ?>checked="checked"<?php endif; ?> />
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Título</label>
      <div>
        <input name="titulo" type="text" class="" value="<?php echo @$data['table']['titulo'] ?>">
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Data</label>
      <div>
        <input name="data" type="text" class="" value="<?php echo @$data['table']['data'] ?>">
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Galeria</label>
      <div>
        <?php echo @$data['table']['galeria_uploader'] ?>
      </div>
    </fieldset>

    {$CADASTRO_SUBMIT}

  </form>

</div>
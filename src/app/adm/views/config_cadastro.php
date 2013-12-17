<div id="top_container">
  <h2>Meus Dados</h2>
  <p>&nbsp;</p>
</div>

{$CADASTRO_ALERTS}

<div class="box">

  <form class="validate_form" method="post" id="main_form">

    <fieldset class="label_side top">
      <label for="required_field">Nome</label>
      <div>
        <input name="nome" type="text" class="required" value="<?php echo @$data['table']['nome'] ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">E-mail</label>
      <div>
        <input name="email" type="text" class="required" value="<?php echo @$data['table']['email'] ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Senha</label>
      <div>
        <input name="senha" type="password">
      </div>
    </fieldset>

    <fieldset class="label_side top">
      <label for="required_field">Avatar</label>
      <div>
        <?php echo @$data['table']['avatar'] ?>
      </div>
    </fieldset>

    {$CADASTRO_SUBMIT}

  </form>

</div>
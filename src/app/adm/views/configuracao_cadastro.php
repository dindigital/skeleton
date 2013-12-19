<div id="top_container">
  <h2>Cadastro de Configuração</h2>
  <p>&nbsp;</p>
</div>

{$CADASTRO_ALERTS}

<div class="box">

  <form class="validate_form" method="post" id="main_form">

    <fieldset class="label_side">
      <label for="required_field">Title Home</label>
      <div>
        <input name="title_home" type="text" class="required" value="<?php echo $data['table']['title_home']; ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Description Home</label>
      <div class="clearfix">
        <textarea name="description_home" class="autogrow"><?php echo $data['table']['description_home']; ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Keywords Home</label>
      <div class="clearfix">
        <textarea name="keywords_home" class="autogrow"><?php echo $data['table']['keywords_home']; ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Title Internas</label>
      <div>
        <input name="title_interna" type="text" class="required" value="<?php echo $data['table']['title_interna']; ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Description Internas</label>
      <div class="clearfix">
        <textarea name="description_interna" class="autogrow"><?php echo $data['table']['description_interna']; ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Keywords Internas</label>
      <div class="clearfix">
        <textarea name="keywords_interna" class="autogrow"><?php echo $data['table']['keywords_interna']; ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Qtd. hora enviar orç.</label>
      <div>
        <input name="qtd_horas" type="text" class="required" value="<?php echo $data['table']['qtd_horas']; ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">E-mail avisos</label>
      <div>
        <input name="email_avisos" type="text" class="required" value="<?php echo $data['table']['email_avisos']; ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>


    {$CADASTRO_SUBMIT}

  </form>

</div>
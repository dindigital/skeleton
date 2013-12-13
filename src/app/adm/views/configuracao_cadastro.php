<div id="top_container">
  <h2>Cadastro de <?= $this->_model->getMe() ?></h2>
  <p>&nbsp;</p>
</div>

<? include 'includes/cadastro_alerts.php' ?>

<div class="box">

  <form class="validate_form" method="post" id="main_form" action="<?= $this->action ?>">

    <fieldset class="label_side">
      <label for="required_field">Title Home</label>
      <div>
        <input name="title_home" type="text" class="required" value="<?= $this->table->title_home ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Description Home</label>
      <div class="clearfix">
        <textarea name="description_home" class="autogrow"><?= $this->table->description_home ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Keywords Home</label>
      <div class="clearfix">
        <textarea name="keywords_home" class="autogrow"><?= $this->table->keywords_home ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Title Internas</label>
      <div>
        <input name="title_interna" type="text" class="required" value="<?= $this->table->title_interna ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Description Internas</label>
      <div class="clearfix">
        <textarea name="description_interna" class="autogrow"><?= $this->table->description_interna ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Keywords Internas</label>
      <div class="clearfix">
        <textarea name="keywords_interna" class="autogrow"><?= $this->table->keywords_interna ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Qtd. hora enviar orç.</label>
      <div>
        <input name="qtd_horas" type="text" class="required" value="<?= $this->table->qtd_horas ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">E-mail avisos</label>
      <div>
        <input name="email_avisos" type="text" class="required" value="<?= $this->table->email_avisos ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>


    <? include 'includes/cadastro_submit.php' ?>

  </form>

</div>
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
      <label for="required_field">Razão Social</label>
      <div>
        <input name="rz_social" type="text" class="required" value="<?= $this->table->rz_social ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">CNPJ</label>
      <div>
        <input name="cnpj" type="text" class="required cnpjMask" value="<?= $this->table->cnpj ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Nome Contato</label>
      <div>
        <input name="nome_contato" type="text" class="required" value="<?= $this->table->nome_contato ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Logradouro</label>
      <div>
        <input name="logradouro" type="text" class="required" value="<?= $this->table->logradouro ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Complemento</label>
      <div>
        <input name="complemento" type="text" class="" value="<?= $this->table->complemento ?>">
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Bairro</label>
      <div>
        <input name="bairro" type="text" class="required" value="<?= $this->table->bairro ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Cidade</label>
      <div>
        <input name="cidade" type="text" class="required" value="<?= $this->table->cidade ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Estado</label>
      <div>
        <input name="estado" type="text" class="required" value="<?= $this->table->estado ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Onde Conheceu</label>
      <div>
        <input name="onde_conheceu" type="text" class="required" value="<?= $this->table->onde_conheceu ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">E-mail</label>
      <div class="clearfix">
        <textarea name="email" class="autogrow"><?= $this->table->email ?></textarea>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Usuário</label>
      <div>
        <input name="usuario" type="text" class="required" value="<?= $this->table->usuario ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Senha</label>
      <div>
        <input name="senha" type="password" class="" value="<?= $this->table->senha ?>">
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Serviços</label>
      <div>
        <?= $this->table->servicos ?>
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Produtos</label>
      <div>
        <?= $this->table->produtos ?>
        <div class="required_tag"></div>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Marcas</label>
      <div>
        <?= $this->table->marcas ?>
        <div class="required_tag"></div>
      </div>
    </fieldset>



    <? include 'includes/cadastro_submit.php' ?>

  </form>

</div>
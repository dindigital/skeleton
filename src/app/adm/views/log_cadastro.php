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

  <form class="validate_form" method="post" id="main_form" action="<?= $this->action ?>">
    <fieldset class="label_side">
      <label for="required_field">Data</label>
      <div>
        <?= \lib\Validation\DateTransform::format_date($this->table->data, 'full') ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Responsável</label>
      <div>
        <?= $this->table->responsavel ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Ação</label>
      <div>
        <?= src\app\adm005\objects\Arrays::arrayLogCrud($this->table->crud) ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Seção</label>
      <div>
        <?= $this->table->secao ?>
      </div>
    </fieldset>

    <fieldset class="label_side">
      <label for="required_field">Descrição</label>
      <div>
        <?= $this->table->descricao ?>
      </div>
    </fieldset>

    <!--
    <? if ( $this->table->crud == 'U' ): ?>
                <fieldset class="label_side">
                  <label for="required_field">Detalhes</label>
                  <div>
      <? if ( count($this->table->content['before']) ): ?>
                                <table class="lista_log" width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <th colspan="2" scope="col"><?= $this->table->nome_legivel; ?> - Antes</th>
                                  </tr>
        <? foreach ( $this->table->content['before'] as $k => $v ): ?>
                                              <tr>
                                                <td width="20%"><?= $k ?>:</td>
                                                <td width="80%"><?= $v ?></td>
                                              </tr>
        <? endforeach; ?>
                                  <tr>
                                    <td class="lista_log_space" colspan="2" scope="col">&nbsp;<td>
                                  </tr>
                                </table>
                                <table class="lista_log" width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <th colspan="2" scope="col"><?= $this->table->nome_legivel; ?> - Depois</th>
                                  </tr>
        <? foreach ( $this->table->content['after'] as $k => $v ): ?>
                                              <tr>
                                                <td width="20%"><?= $k ?>:</td>
                                                <td width="80%"><?= $v ?></td>
                                              </tr>              <? endforeach; ?>
                                </table>
      <? else: ?>
                                Nenhum campo foi alterado.
      <? endif; ?>
                  </div>
                </fieldset>

    <? elseif ( $this->table->crud == 'C' || ($this->table->crud == 'I' && isset($this->table->content['insert'])) ): ?>
                <fieldset class="label_side">
                  <label for="required_field">Detalhes</label>
                  <div>
                    <table class="lista_log" width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <th colspan="2" scope="col"><?= $this->table->nome_legivel; ?></th>
                      </tr>
      <? foreach ( $this->table->content['insert'] as $k => $v ): ?>
                                  <tr>
                                    <td width="20%"><?= $k ?>:</td>
                                    <td width="80%"><?= $v ?></td>
                                  </tr>
      <? endforeach; ?>
                    </table>
                  </div>
                </fieldset>

    <? elseif ( in_array($this->table->crud, array('D')) ): ?>
                <fieldset class="label_side">
                  <label for="required_field">Detalhes</label>
                  <div>
                    <table class="lista_log" width="100%" border="0" cellspacing="0" cellpadding="0">
      <? foreach ( $this->table->content as $secao => $contents ): ?>
                                  <tr>
                                    <th colspan="2" scope="col"><?= $secao ?></th>
                                  </tr>
        <? foreach ( $contents as $content ): ?>
          <? foreach ( $content as $k => $v ): ?>
                                                          <tr>
                                                            <td width="20%"><?= $k ?>:</td>
                                                            <td width="80%"><?= $v ?></td>
                                                          </tr>
          <? endforeach; ?>
                                              <tr>
                                                <td class="lista_log_space" colspan="2" scope="col">&nbsp;<td>
                                              </tr>
        <? endforeach; ?>
      <? endforeach; ?>
                    </table>
                  </div>
                </fieldset>
    <? endif; ?>
    -->

    <? include 'includes/cadastro_submit.php' ?>

  </form>

</div>
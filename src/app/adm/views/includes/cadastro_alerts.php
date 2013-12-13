<div class="alert dismissible alert_red" id="alert_erro" style="display: none">
  <img height="24" width="24" src="/backend/images/alarm_bell.png">
  <span>Este CPF está em um formato inválido, por favor verificar!</span>
</div>


<div class="alert dismissible alert_green" id="alert_salvo" <? if ( !isset($this->registro_salvo) ): ?>style="display: none"<? endif ?>>
  <img height="24" width="24" src="/backend/images/alert_2.png">
  Registro salvo com sucesso!
</div>

<div class="alert dismissible alert_green" id="alert_twit" <? if ( !isset($this->twit_salvo) ): ?>style="display: none"<? endif ?>>
  <img height="24" width="24" src="/backend/images/alert_2.png">
  Twit enviado com sucesso!
</div>

<div class="alert dismissible alert_green" id="cache_clean" <? if ( !isset($this->cache_clean) ): ?>style="display: none"<? endif ?>>
  <img height="24" width="24" src="/backend/images/alert_2.png">
  Cache esvaziado com sucesso!
</div>
<div class="alert dismissible alert_red" id="alert_erro" style="display: none">
  <img height="24" width="24" src="/adm/images/alarm_bell.png">
  <span></span>
</div>

<div class="alert dismissible alert_green" id="alert_salvo" <?php if ( $data['registro_salvo'] !== true ): ?>style="display: none"<?php endif ?>>
  <img height="24" width="24" src="/adm/images/alert_2.png">
  Registro salvo com sucesso!
</div>
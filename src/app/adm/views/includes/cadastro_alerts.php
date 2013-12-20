<div class="alert dismissible alert_red" id="alert_erro" style="display: none">
  <img height="24" width="24" src="/adm/images/alarm_bell.png">
  <span></span>
</div>

<?php if ( isset($data['registro_salvo']) ): ?>
  <div class="alert dismissible alert_green" id="alert_salvo" style="display: none">
    <img height="24" width="24" src="/adm/images/alert_2.png">
    <span><?php echo $data['registro_salvo']; ?></span>
  </div>
<?php endif; ?>
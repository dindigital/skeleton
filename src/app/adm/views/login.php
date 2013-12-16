<form id="containerLogin" class="boxshadow boxradios validate_form" method="post">
  <fieldset class="label_side top">
    <label for="email">E-mail</label>
    <div>
      <input type="text" id="email" name="email" class="required email">
    </div>
  </fieldset>
  <fieldset class="label_side">
    <label for="senha">Senha</label>
    <div>
      <input type="password" id="senha" name="senha" class="required">
    </div>
  </fieldset>
  <div class="button_bar clearfix">
    <button type="submit" class="boxradios right">
      <img src="/adm/images/key_2.png">
      <span>Acessar</span>
    </button>
  </div>
</form>
<div id="login_error" style="color: #F00; margin:15px auto; width:370px"></div>
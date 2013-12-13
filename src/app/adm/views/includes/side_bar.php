<!-- SIDE BAR -->
<div id="sidebar" class="boxradios">

  <!-- CONTAINER PROFILE -->
  <div id="containerProfile" class="boxradios">
    <?php echo $data['user']['avatar'] ?>
    <h2>Administrador</h2>
    <h3><a class="replace_container" href="/adm/config/cadastro/" title="Configurações"><?php echo $data['user']['nome'] ?></a></h3>
    <ul>
      <li>
        <a class="replace_container" href="/adm/config/cadastro/" title="Configurações">Config</a>
        <span class="divider">|</span>
      </li>
      <li><a href="/adm/usuario_auth/logout/" title="Sair do painel administrativo">Sair</a></li>
    </ul>
  </div>
  <!-- FIM CONTAINER PROFILE -->

  <!-- NAV SIDE -->
  <div id="nav_side">
    <a class="replace_container" href="/adm/configuracao/cadastro/" title="Configurações">Configurações</a>
  </div>
  <!-- FIM NAV SIDE -->


</div>
<!-- FIM BAR -->
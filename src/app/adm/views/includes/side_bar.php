<!-- SIDE BAR -->
<div id="sidebar" class="boxradios">

  <!-- CONTAINER PROFILE -->
  <div id="containerProfile" class="boxradios">
    <?//= $this->avatar ?>
    <h2>Administrador</h2>
    <h3><a class="replace_container" href="/adm/config/cadastro/" title="Configurações">NOME<?//= $this->user_table->nome ?></a></h3>
    <ul>
      <li>
        <a class="replace_container" href="/adm/config/cadastro/" title="Configurações">Config</a>
        <span class="divider">|</span>
      </li>
      <li><a href="/adm/usuariologin/logout/" title="Sair do painel administrativo">Sair</a></li>
    </ul>
  </div>
  <!-- FIM CONTAINER PROFILE -->

  <!-- NAV SIDE -->
  <div id="nav_side">
    <a class="replace_container" href="configuracao/cadastro/" title="Configurações">Configurações</a>
  </div>
  <!-- FIM NAV SIDE -->


</div>
<!-- FIM BAR -->
<div id="top_container">
  <h2>Lista de Usuários</h2>
</div>


<div id="search_table">
  <form method="get" class="form_busca">
    <input type="hidden" id="link_prefix" value="/adm/usuario/" />
    <span id="magicfields"></span>
    <?php // include 'includes/hidden_fields.php' ?>

    <input name="nome" type="text" placeholder="Busca por nome" value="<?php echo $data['busca']['nome']; ?>">
    <input name="email" type="text" placeholder="Busca por email" value="<?php echo $data['busca']['email']; ?>">

    {$BTN_LIMPAR}
    {$BTN_BUSCAR}
  </form>
</div>

<div class="button_bar clearfix">
  <button type="button" class="link boxradios" href="/adm/usuario/cadastro/">
    <img height="24" width="24" alt="Voltar para a lista" src="/adm/images/create_write.png">
    <span>Novo Registro</span>
  </button>
  {$BTN_EXCLUIR_PERMANENTEMENTE}
</div>

<div class="box">
  <table class="datatable">
    <thead>
      <tr>
        <th class="typeCheck"><input class="uniform" type="checkbox" name="excluir[]" id="excluir_all" /></th>
        <th class="typeStatus">Ativo</th>
        <th class="txtleft">Nome</th>
        <th class="txtleft" style="width:300px">E-mail</th>
        <th class="txtleft" style="width:150px">Data de Inclusão</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ( $data['list'] as $row ): ?>
        <tr>
          <td class="typeCheck"><input class="uniform excluir" type="checkbox" name="excluir[]" id="exc_usuario_<?php echo $row['id_usuario']; ?>" /></td>
          <td class="typeStatus"><input class="uniform setAtivo" type="checkbox" <?php if ( $row['ativo'] == '1' ): ?> checked="checked"<?php endif; ?> id="<?php echo $row['id_usuario']; ?>" /></td>
          <td>
            <?php echo $row['nome']; ?>
            <ul>
              <li><a href="/adm/usuario/cadastro/<?php echo $row['id_usuario']; ?>/" title="Editar registro" class="replace_container">Editar</a></li>
              <li><a class="excluir_shortcut" title="Enviar para lixeira">Excluir</a></li>
            </ul>
          </td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $data['DateTransform']::format_date($row['inc_data']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  {$LISTA_FOOTER}
</div>

